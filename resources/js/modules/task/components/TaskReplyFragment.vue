<template>
    <div class="task-reply">
        <div class="mb-5" v-if="!taskReply?.id">

<!--            <p class="text-decoration-underline">
                Прикрепите файлы, необходимые для проверки выполнения задачи.
                Для этого нажмите на кнопку <b>Добавить</b>
            </p>-->

            <!--<h6 class="h4 mb-4">Ваш ответ</h6>-->

            <div class="form-group mb-3">
                <label for="desc" class="form-label fw-bold">Описание (можно оставить пустым)</label>
                <textarea class="form-control" id="desc" rows="3" ref="description"></textarea>
            </div>

            <div class="mb-4" v-show="executedTaskFilesExists">
                <div class="form-label fw-bold">Файлы ответа</div>
                <ul class="list-group">
                    <li class="list-group-item" v-for="file in reviewableTaskFiles" :key="file.generated_name">
                        <div class="d-flex justify-content-between">
                            <div>{{ file.original_name }}</div>
                            <div class="cursor-pointer px-2 text-danger bi-x-lg" @click="removeFile"
                                 :data-file-id="file.id" :data-file-name="file.original_name">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <button class="btn btn-sm btn-warning mb-3" :disabled="!executedTaskFilesExists"
                    @click="submitTaskForReview">
                Отправить на проверку
            </button>

        </div>

        <div class="mb-5" v-else>

            <div class="card">
                <div :class="`card-header fw-bold text-white bg-${review.clazz}`">
                    {{ review.caption }}
                    (отправлено на проверку - {{ dt_format(taskReply.created_at) }})
                </div>
                <div class="card-body">
                    <div class="mb-3" v-show="!!taskReply.description">
                        {{ taskReply.description }}
                    </div>

                    <ul class="list-group list-group-numbered list-group-flush ms-0" v-show="taskReply?.files?.length > 0">
                        <li class="list-group-item ps-0" v-for="file in taskReply?.files" :key="file.generated_name">
                            <a :href="'/upload/' + file.generated_name" target="_blank">{{ file.original_name }}</a>
                        </li>
                    </ul>

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <select class="form-select form-select-sm w-25" v-model="selectedReviewType">
                        <!-- <option disabled value="">Выберите тип ответа</option> -->
                        <option :value="reviewType" v-for="reviewType in reviewTypes">{{ reviewType.caption }}</option>
                    </select>
                    <button :class="`btn btn-sm btn-outline-${review.clazz}`" @click="onReviewSave">Сохранить</button>
                </div>
            </div>

            <div class="card my-3" v-show="canShowReviewReplyForm">
                <div class="card-header fw-bolder">
                    <i class="bi bi-info-circle me-1"></i>
                    <span class="fw-lighter">Причина</span>
                </div>
                <div class="card-body p-2">
                    <textarea class="form-control" rows="3" ref="reviewText"></textarea>
                </div>
<!--                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary">Отправить</button>
                </div>-->
            </div>

        </div>
    </div>
</template>

<script>
import FileUpload from "@/common/components/FileUpload.vue";
import {ref_value} from "@/utils/index.js";
import {filesService} from "@/modules/files/api/FilesService.js";
import {taskReplyService} from "@/modules/task/api/TaskReplyService.js";

export default {
    name: "TaskReplyFragment",
    components: {FileUpload},
    props: {
        taskReplyData: {
            type: Object,
            required: true
        },
        lectureId: {
            type: Number,
            required: true
        },
        taskId: {
            type: Number,
            required: true
        },
    },

    data() {
        return {
            taskReply: null,
            reviewableTaskFiles: [],
            reviewTypes: [
                { name: 'accepted', caption: 'Принято', clazz: 'success' },
                { name: 'rejected', caption: 'Не принято', clazz: 'danger' },
                { name: 'returned', caption: 'Возвращено на доработку', clazz: 'secondary' },
                { name: 'wait_for_review', caption: 'Ожидает проверки', clazz: 'primary' },
            ],
            selectedReviewType: { name: 'wait_for_review', caption: 'Ожидает проверки', clazz: 'primary' }
        }
    },

    mounted() {
        this.taskReply = this.taskReplyData;
        //console.log(this.taskReply);
    },

    // watch: {
    //     'taskReply'(newValue) {
    //         this.taskReply = newValue;
    //     }
    // },

    computed: {

        review() {
            return this.selectedReviewType || this.reviewFrom(this.taskReply.review_status);
        },

        canShowReviewReplyForm() {
            return (this.selectedReviewType?.name === 'returned') || (this.selectedReviewType?.name === 'rejected');
        },

        hasFiles() {
            return this.userTask?.files?.length > 0;
        },

        executedTaskFilesExists() {
            return this.reviewableTaskFiles?.length > 0;
        }
    },

    methods: {

        onReviewSave() {
            const message = this.$refs.reviewText.value;

            const comment = {
                commentable_type: 'task',
                commentable_id: this.taskReply.id,
                message
            }

            console.log(comment);
        },

        reviewFrom(name) {
            return this.reviewTypes.find(rt => rt.name === name);
        },

        onFileUploaded(file) {
            this.reviewableTaskFiles = [...this.reviewableTaskFiles, file];
        },

        async removeFile(e) {
            const {fileId, fileName} = e.target.dataset;

            if (!confirm(`Вы действительно хотите удалить файл ${fileName} ?`)) return;

            try {
                await filesService.remove(fileId);
                this.reviewableTaskFiles = this.reviewableTaskFiles.filter(_file => +_file.id !== +fileId);

            } catch (e) {
                alert(e.statusText || e.response.data.message);
            }
        },

        async submitTaskForReview() {

            const value = ref_value(this);

            const fields = {
                description: value('description'),
                lecture_id: this.lectureId,
                task_id: this.taskId,
                files: [...this.reviewableTaskFiles.map(file => file.id)],
            };

            try {
                const {data} = await taskReplyService().create(fields);
                //console.log(data);
                this.taskReply = data;
            } catch (e) {
                alert(e.message);
            }

        }
    }
}
</script>

<style scoped>

</style>
