<template>
    <dashboard-wrapper :has-content="hasContent" :is-loading="isLoading">
        <page-header :caption="`#${task.id} ${task.title}`">
            <button class="btn btn-sm btn-outline-primary" @click="goBack">Вернуться</button>
        </page-header>

        <!-- Todo: comments files is not reliable for conditional render -->
        <image-modal v-if="comment_files.length === 0" id="taskImageModal" :src="review_image" title="Изображение"/>

        <modal-dialog v-else id="taskImageModal" title="Image">
            <image-slider :images="comment_files"/>
        </modal-dialog>

        <template v-if="!task.is_visible">
            <div class="mx-1">
                <h5 class="h5">Задание недоступно, делайте другие :)</h5>
            </div>
        </template>

        <template v-else>
            <div v-if="is_task_has_deadline" class="mx-1 mb-3">
                <div class="mb-2 me-2 d-inline-block">Задание нужно сдать до:</div>
                <time v-if="!is_admin()" class="bg-warning-subtle">{{ dt_format2(task.expired_at) }}</time>
                <input v-else
                       :value="task.expired_at"
                       class="form-control form-control-sm"
                       max="2030-01-01T00:00"
                       min="2023-01-01T00:00"
                       type="datetime-local"
                       @change="onTaskDueDateChange"
                />
            </div>

            <div class="card">

                <div v-if="isShowTaskReviewHeader"
                     :class="`card-header d-flex justify-content-between bg-${review?.clazz}-subtle`">
                    <div :class="`badge bg-${review?.clazz} d-inline-block`">{{ review?.caption }}</div>
                    <div class="text-secondary">{{ dt_format2(selectedStudentReview?.updated_at) }}</div>
                </div>

                <div class="card-body">
                    <p v-html="task.description"></p>

                    <ul v-if="hasFiles" class="list-group list-group-numbered list-group-flush">
                        <li v-for="file in task.files" :key="file.generated_name" class="list-group-item ps-0">
                            <a :href="'/upload/' + file.generated_name" target="_blank">{{ file.original_name }}</a>
                        </li>
                    </ul>
                </div>
                <div v-if="isShowStudentsDropdown" class="card-footer d-flex justify-content-between">
                    <select v-model="selectedReviewType" class="form-select form-select-sm w-25"
                            title="Выберите статус">
                        <!-- <option disabled value="">Выберите тип ответа</option> -->
                        <option v-for="reviewType in reviewTypes" :value="reviewType.name">
                            {{ reviewType.caption }}
                        </option>
                    </select>
                    <button :class="`btn btn-sm btn-${review.clazz}`" @click="onReviewSave">Изменить статус</button>
                </div>
            </div>

            <div v-if="showAnswerForm" class="card my-3">

                <div class="card-body px-3">
                <textarea ref="message" v-model="userTaskCommentText" class="form-control" placeholder="Введите ответ здесь..."
                          required rows="3"></textarea>

                    <div v-if="answer_attached_files?.length > 0" class="row mt-3 mb-3">
                        <div class="col me-3" @click="onForReviewTaskFileClick">
                            <div v-for="(file, counter) in answer_attached_files"
                                 :data-name="file.name" class="d-inline-block position-relative me-2">
                                <remove-icon class="text-danger cursor-pointer position-absolute remove-icon"
                                             @click="onReviewTaskFileRemove"/>
                                <img :key="counter"
                                     :alt="file.name"
                                     :data-name="file.name"
                                     :src="file.data ?? images_path('file-type-01.png')"
                                     class="img-fluid img-thumbnail image64px me-2"
                                     data-bs-target="#taskImageModal"
                                     data-bs-toggle="modal"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <file-chooser
                        id="choose_review_image"
                        caption="Добавить файл"
                        class="btn btn-sm btn-outline-primary"
                        @on-file-choose="onTaskFileChoose"
                    />
                    <button class="btn btn-sm btn-primary" @click="sendForReview">
                        Отправить на проверку
                    </button>
                </div>
            </div>


            <div v-if="is_admin()" class="mt-5 row">
                <div class="col-md-8">
                    <h4 class="h4">История задачи</h4>
                </div>
                <div class="col-md-4">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-4 pt-1">
                            <div class="form-check">
                                <input id="userTaskPageAllStudentsCheckbox" v-model="load_all_students" class="form-check-input"
                                       type="checkbox" value="">
                                <label class="form-check-label cursor-pointer" for="userTaskPageAllStudentsCheckbox">
                                    Все студенты
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <select v-model="selectedStudentId" class="form-select form-select-sm col-md-4"
                                    required @change="onStudentSelected">
                                <option disabled selected value="0">Выберите студента...</option>
                                <option v-for="(student, counter) in students" :value="student.id">
                                    #{{ ++counter }} {{ student.lastname }} {{ student.firstname }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="mt-5 row">
                <h4 class="h4">История задачи</h4>
            </div>

            <hr class="fw-bold mt-2 mb-4">

            <div v-if="isShowCommentForm" class="card my-3">

                <div class="card-body px-3">
                    <textarea ref="comment_message"
                              v-model="comment_message" class="form-control"
                              placeholder="Можете спросить, если, что не понятно..." required rows="3">
                    </textarea>
                </div>

                <!--<div class="ms-3 mb-3" v-if="commentFiles.length > 0">
                    <ul class="list-group list-group-numbered list-group-flush">
                    <li class="list-group-item ps-0" v-for="file in commentFiles" :key="file.generated_name">
                    <a :href="'/upload/' + file.generated_name">{{ file.original_name }}</a>
                    </li>
                    </ul>
                </div>-->

                <div class="card-footer d-flex justify-content-end">
                    <!--<file-upload caption="Добавить файл" @on-upload="onFileUpload"/>-->
                    <button :disabled="comment_message.length < 10" class="btn btn-sm btn-primary" @click="sendComment">
                        Добавить
                    </button>
                </div>
            </div>

            <template v-if="has_comments">
                <div v-for="comment in comments" class="card my-3 position-relative">

                    <div v-if="is_admin()" :data-comment-id="comment.id"
                         class="position-absolute text-danger fw-lighter cursor-pointer z-0"
                         style="bottom: 10px; right: 10px; z-index: 1100"
                         @click="removeComment">
                        Удалить
                    </div>

                    <div class="card-header text-secondary d-flex justify-content-between">
                        <div v-if="comment.user.role === 'admin'">
                            {{ getReviewByStatusName(comment?.status)?.caption }} - Преподаватель
                        </div>
                        <div v-else>
                            {{ getReviewByStatusName(comment?.status)?.caption }} - {{ comment.user?.lastname }}
                            {{ comment.user?.firstname }}
                        </div>
                        <div>{{ dt_format2(comment.created_at) }}</div>
                    </div>

                    <div class="card-body px-3">
                        <span v-html="nl2br(comment.message)"></span>
                        <div v-if="comment.files?.length > 0" class="my-3">
                            <files-list :list="comment.files" target-modal="#taskImageModal"
                                        @on-image-click="onTaskStatusImageClick"/>
                        </div>
                    </div>

                </div>
            </template>
        </template>

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {usersService} from "@/modules/users/api/UsersService.js";
import {tasksService} from "@/modules/task/api/TasksService.js";
import {commentsService} from "@/modules/task/api/CommentsService.js";
import {reviewService} from "@/modules/task/api/ReviewService.js";
import {filesService} from "@/modules/files/api/FilesService.js";
import FileUpload from "@/common/components/FileUpload.vue";
import NavTabs from "@/common/components/navTabs/NavTabs.vue";
import TaskReplyFragment from "@/modules/task/components/TaskReplyFragment.vue";
import NavTabLink from "@/common/components/navTabs/NavTabLink.vue";
import NavTabContent from "@/common/components/navTabs/NavTabContent.vue";
import NavTabPane from "@/common/components/navTabs/NavTabPane.vue";
import {ref_value} from "@/utils/index.js";
import {defaultType, getReviewByStatusName, reviewTypes} from "@/utils/reviewTypes.js";
import PageKeyup from "@/common/components/PageKeyup.vue";
import {mapGetters, mapMutations} from "vuex";
import FileChooser from "@/common/components/FileChooser.vue";
import readFileAsDataURL from "@/utils/readAsDataUrl.js";
import {pngToJpeg} from "@/utils/pngToJpeg.js";
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";
import ImageModal from "@/common/components/ImageModal.vue";
import FilesList from "@/common/components/FilesList.vue";
import ModalDialog from "@/common/components/ModalDialog.vue";
import ImageSlider from "@/common/components/slider/ImageSlider.vue";
import {nl2br} from "@/utils/nl2br.js";
import {is_image} from "@/utils/file.js";

export default {
    name: "UserTaskPage",
    components: {
        ImageSlider,
        ModalDialog,
        FilesList,
        ImageModal,
        RemoveIcon,
        FileChooser,
        PageKeyup,
        NavTabPane,
        NavTabContent, NavTabLink, TaskReplyFragment, NavTabs, FileUpload, PageHeader, DashboardWrapper
    },
    data() {
        return {
            task: {
                students: []
            },
            isExecuteTaskOpen: false,
            reviewableTaskFiles: [],
            reviewableTask: {},
            isLoading: false,
            hasContent: false,
            reviewTypes: reviewTypes(),
            selectedReviewType: null,
            // commentFiles: [],
            selectedStudentId: 0,
            selectedStudentComments: [],
            selectedStudentReview: null,
            comment_message: '',
            load_all_students: false,
            all_students: [],

            // images that user sent to review initially
            review_images_data: [],
            review_images: [],
            review_files: [],
            review_image: '',
            comment_files: [],
        }
    },
    async mounted() {

        if (this.is_admin()) {
            const resStudents = await usersService().getStudents();
            this.all_students = resStudents.data;
        }

        this.isLoading = true;
        try {
            this.task = await this.getTask();
        } catch (e) {
            if (e.response?.status === 404) {
                this.hasContent = false;
            }
            console.error(e);
        } finally {
            this.isLoading = false;
        }

        const {student_id} = this.$route.query;
        if (student_id) {
            this.selectedStudentId = +student_id;
            await this.onStudentSelected();
        }
    },

    computed: {

        ...mapGetters('taskStore', ['_userTaskCommentText', '_userTaskCommentFiles']),

        userTaskCommentText: {
            get() {
                return this._userTaskCommentText(this.task.id);
            },
            set(value) {
                this.UPDATE_COMMENT_TEXT({task_id: this.task.id, text: value});
            }
        },

        userTaskCommentFiles: {
            get() {
                return this._userTaskCommentFiles(this.task.id);
            },
        },

        is_task_has_deadline() {
            return this.is_admin() || (null !== this.task?.expired_at)
        },

        keyboardEvent(e) {
            console.log(e);
        },

        isShowTaskReviewHeader() {
            return !this.is_admin() || this.selectedStudentId !== 0;
        },

        disabled() {
            return this.selectedStudentId === 0;
        },

        isShowCommentForm() {
            return !(['wait_for_review', 'rejected'].includes(this.review.name)) && (this.selectedStudentId !== 0);
        },

        showAnswerForm() {
            return !this.is_admin() && !(['accepted', 'wait_for_review', 'rejected'].includes(this.review.name));
        },

        isShowStudentsDropdown() {
            return this.is_admin() && (this.selectedStudentId !== 0) && (this.students?.length > 0);
        },

        has_comments() {
            return (this.is_admin() && this.selectedStudentComments.length > 0) || this.task.comments?.length > 0;
        },

        comments() {
            if (this.selectedStudentComments?.length > 0) {
                return this.selectedStudentComments;
            }

            if (this.task.comments?.length > 0) {
                return this.task.comments;
            }

            return [];
        },

        review() {
            return getReviewByStatusName(this.selectedReviewType) || getReviewByStatusName(this.task?.review_status) || defaultType;
        },

        hasFiles() {
            return this.task?.files?.length > 0;
        },

        executedTaskFilesExists() {
            return this.reviewableTaskFiles.length > 0;
        },

        students() {
            // console.log(this.load_all_students);
            return this.load_all_students ? this.all_students : this.task.students;
        },

        answer_attached_files() {
            return [
                ...this.review_files,
                ...this.review_images_data
            ];
        }
    },
    methods: {
        getReviewByStatusName,
        nl2br,

        ...mapMutations('taskStore', ['UPDATE_COMMENT_TEXT', 'UPDATE_COMMENT_FILES', 'CLEAR_TASK_COMMENT']),

        /**
         *
         * @param file {File}
         * @return {Promise<void>}
         */
        async onTaskFileChoose(file) {

            if(!is_image(file)) {
                this.review_files = [...this.review_files, file];
                return;
            }

            readFileAsDataURL(file)
                .then(async (result) => {

                    this.review_images_data = [...this.review_images_data, {name: file.name, data: result}];

                    const blob = await pngToJpeg(file);
                    const image = new File([blob], file.name, {type: 'image/jpeg'});
                    this.review_images = [...this.review_images, {name: file.name, data: image}];

                    this.UPDATE_COMMENT_FILES(this.task.id, file.name, image);
                })
                .catch((error) => {
                    alert("Ошибка при добавлении файла!");
                    console.log(error);
                });
        },

        onForReviewTaskFileClick(e) {
            if (!(e.target instanceof HTMLImageElement)) return;
            const image = this.review_images_data.find(image => image.name === e.target.dataset.name);

            if (image) {
                this.review_image = image.data;
            }
        },

        /**
         *
         * @param selected {string} selected file name
         * @param files {Array} all files
         */
        onTaskStatusImageClick({selected, files}) {
            this.comment_files = files;
            this.review_image = this.upload_path({selected});
        },

        onReviewTaskFileRemove(e) {
            const image_name = e.target.closest('div').dataset.name;

            if (image_name) {
                this.review_images = this.review_images.filter(image => image.name !== image_name);
                this.review_images_data = this.review_images_data.filter(image => image.name !== image_name);
            }
        },

        async onTaskDueDateChange(e) {
            const expired_at = Date.parse(e.target.value);
            // console.log(expired_at);

            const {data} = await tasksService().update(this.task.id, {expired_at});
            // console.log(data);
        },

        generateImageViewerLink(file, task_id) {

            const route = this.$router.resolve({
                name: 'imageviewer',
                params: {image: file.generated_name},
                query: {
                    ...this.$route.query,
                    task_id,
                    student_id: file.user_id,
                }
            });
            return route.fullPath;
        },

        fileIsImage(file) {
            return ['image/jpeg', 'image/png'].includes(file.type);
        },

        async onReviewSave() {

            const value = ref_value(this);

            try {
                if (0 === +this.selectedStudentId) {
                    alert('Выберите студента для оценки работы!');
                    return;
                }

                /*if (0 === this.comments.length) {
                    alert('Нечего оценивать!');
                    return;
                }*/

                /*const review = {
                    lecture_id: this.task.lecture_id,
                    task_id: this.task.id,
                    review_status: this.review.name,
                    student_id: this.selectedStudentId,
                    //reviewer_id: ,
                }
*/

                const req = {
                    task_id: this.task.id,
                    message: value('message'),
                    status: this.review.name,
                    student_id: this.selectedStudentId,
                }

                const {data} = await tasksService().updateTaskHistory(req);
                this.selectedStudentComments = [...this.selectedStudentComments, data];
                this.task.review_status = data.status;


                //const {data} = await reviewService().create(review);
                alert('Статус задания изменен!')
                //console.log(data);
            } catch (e) {
                alert(e);
            }
        },

        async removeComment(e) {
            const comment_id = +e.target.dataset.commentId;

            await commentsService().remove(comment_id);

            if (this.is_admin()) {
                this.selectedStudentComments = this.selectedStudentComments.filter(c => +c.id !== comment_id);
            } else {
                this.task.commments = this.task.comments.filter(c => +c.id !== comment_id);
            }
        },

        async onStudentSelected() {

            this.selectedStudentComments = [];

            const {data} = await tasksService().comments(this.task.id, this.selectedStudentId);
            this.selectedStudentComments = [...this.selectedStudentComments, ...data];

            const res = await reviewService().getReviewBy(this.task.id, this.selectedStudentId);
            const status = res.data.review_status;
            this.selectedStudentReview = res.data;

            this.selectedReviewType = getReviewByStatusName(status)?.name;

            // change query string to upload
            this.$router.replace({query: {student_id: this.selectedStudentId}});
        },

        clearCommentForm() {
            this.CLEAR_TASK_COMMENT();
            this.$refs.message.value = '';
            this.review_images = [];
            this.review_images_data = [];
        },

        onFileUpload(file) {
            this.UPDATE_COMMENT_FILES({task_id: this.task.id, file: file});
        },

        async sendComment() {
            if (this.is_admin() && this.selectedStudentId === 0) {
                alert('Невозможно добавить комментарий для самого себя!');
                return;
            }

            try {

                const _images = this.review_images.map(image => image.data);

                const req = {
                    task_id: this.task.id,
                    message: this.comment_message?.trim() || '',
                    status: this.review.name,
                    student_id: this.selectedStudentId,
                    files: [..._images, ...this.review_files],
                }

                const {data} = await tasksService().updateTaskHistory(req);
                this.selectedStudentComments = [...this.selectedStudentComments, data];
                this.task.review_status = data.status;

                this.comment_message = '';
            } catch (e) {
                console.error(e);
                alert(e);
            }
        },

        async sendForReview() {

            if (!confirm('Вы действительно хотите отправить ответ на проверку?')) return;

            if (this.is_admin() && this.selectedStudentId === 0) {
                alert('Невозможно добавить комментарий для самого себя!');
                return;
            }

            try {
                const value = ref_value(this);


                if (this.is_admin()) {

                    const _images = this.review_images.map(image => image.data);

                    const req = {
                        task_id: this.task.id,
                        message: this.userTaskCommentText?.trim() || '',
                        status: this.review.name,
                        student_id: this.selectedStudentId,
                        files: [..._images, ...this.review_files],
                    }

                    const {data} = await tasksService().updateTaskHistory(req);
                    this.selectedStudentComments = [...this.selectedStudentComments, data];
                    this.task.review_status = data.review_status;
                } else {

                    const _images = this.review_images.map(image => image.data);

                    const req = {
                        task_id: this.task.id,
                        message: value('message'),
                        status: this.review.name,
                        files: [..._images, ...this.review_files],
                    }

                    const {data} = await tasksService().sendForReview(req);
                    this.task.comments = [...this.task.comments, data];
                    this.task.review_status = data.status;
                }


                this.clearCommentForm();
                window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
            } catch (e) {
                alert(e);
            }

        },

        async goBack() {
            if (this.$route.query.from === 'tasks.reports') {
                await this.$router.push({name: 'tasks.reports', query: {page: this.$route.query.page}});
            } else {
                await this.$router.push('/tasks');
            }
        },

        // async getReviewableTask(lecture_id, task_id) {
        //     try {
        //         const {data} = await reviewableTaskService.getReviewableTask(lecture_id, task_id);
        //         return data;
        //     } catch (e) {
        //         alert(e.message)
        //     }
        // },

        async getTask(all_students = false) {

            try {
                const {id} = this.$route.params;

                const {data} = await tasksService().getById(id, {}, {
                    all_students
                });

                return data;
            } catch (e) {
                alert(e.statusText || e.response.data.message)
            }
        },

        async removeFile(e) {
            const {fileId, fileName} = e.target.dataset;

            if (!confirm(`Вы действительно хотите удалить файл ${fileName} ?`)) return;

            try {
                await filesService().remove(fileId);
                this.reviewableTaskFiles = this.reviewableTaskFiles.filter(_file => +_file.id !== +fileId);

            } catch (e) {
                alert(e.statusText || e.response.data.message);
            }
        }
    }
}
</script>

<style scoped>
.remove-icon {
    top: -10px;
    right: -5px;
    width: 22px;
    height: 22px
}
</style>
