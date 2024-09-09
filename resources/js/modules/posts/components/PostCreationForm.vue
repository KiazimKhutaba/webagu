<template>
<!--    <choose-files-dialog target-id="addFiles2TaskDialog" @on-files-added="addFiles"/>-->

    <form method="POST" enctype="multipart/form-data" @submit.prevent="handleSubmit" class="mb-3">

        <div class="my-3">
            <button type="submit" class="btn btn-sm btn-outline-success">{{
                    isEditingMode ? 'Обновить' : 'Добавить'
                }}
            </button>
            <input type="button" class="btn btn-sm btn-outline-warning ml-10px" @click="clearForm"
                   value="Очистить форму"/>
            <!--<input type="button" class="btn btn-sm btn-outline-danger ml-10px me-2" @click="" value="Удалить"
                   v-if="true"/>

            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#addFiles2TaskDialog">
                Добавить файл
            </button>-->
        </div>

        <div class="my-3">
            <label for="title" class="form-label fw-bold">Заголовок</label>
            <input type="text" class="form-control" id="title" aria-describedby="title" ref="title"/>
        </div>

        <!--<div class="my-3">
            <label for="task_for" class="form-label fw-bold">Тема</label>
            <select class="form-select" id="task_for" aria-label="Тема, к которой относится задача" ref="lecture_id" required>
                <option value="" selected disabled>Выберите тему</option>
                <option :value="lecture.id" v-for="lecture in lectures" :key="lecture.id">{{ lecture.title }}</option>
            </select>
        </div>-->

        <div class="mb-3">
            <label for="content" class="form-label fw-bold">Сообщение</label>
            <div class="form-control min-h-200px" contenteditable="true" ref="content"></div>
        </div>

        <!--<div class="mb-3" v-if="hasFiles">
            <div class="form-label fw-bold">Файлы</div>
            <ul class="list-group">
                <li class="list-group-item" v-for="file in files" :key="file.generated_name">
                    <div class="d-flex justify-content-between">
                        <div>{{ file.original_name }}</div>
                        <div class="cursor-pointer px-2 text-danger bi-x-lg" @click="removeFile"
                             :data-file-id="file.id"></div>
                    </div>
                </li>
            </ul>
        </div>-->

<!--
        <div class="form-check mb-3 mr-sm-2">
            <input class="form-check-input" type="checkbox" id="openForViewing" ref="is_visible">
            <label class="form-check-label" for="openForViewing">
                Задача открыта для выполнения
            </label>
        </div>
-->

    </form>
</template>

<script>

import FileUpload from "@/common/components/FileUpload.vue";
import {ref_value} from "@/utils/index.js";
import {postsService} from "@/modules/posts/api/PostsService.js";
import ChooseFilesDialog from "@/common/components/ChooseFilesDialog.vue";

export default {
    name: "PostCreationForm",
    components: {ChooseFilesDialog, FileUpload},
    emits: ['onPostCreated'],
    props: {
        isEditingMode: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            files: [],
            lectures: []
        }
    },
    async mounted() {

        /*try {
            const {data} = await lecturesService.pluck(['id', 'title']);
            this.lectures = data;
        } catch (e) {
            alert(e.statusText || e.response.data.message);
        }*/
    },
    computed: {

        /*hasFiles() {
            return this.files.length > 0;
        }*/
    },
    methods: {

        /*addFiles(files) {
            this.files = [...this.files, ...files];
        },*/

        getForm() {

            const value = ref_value(this);

            return {
                title: value('title'),
                content: value('content'),
                // is_visible: Number(value('is_visible')),
                // lecture_id: value('lecture_id'),
                //files: [...this.files.map(file => file.id)],
            };
        },

        /*async removeFile(e) {

            try {
                const fileId = e.target.dataset.fileId;
                await filesService.remove(fileId);

                this.files = this.files.filter(file => +file.id !== +fileId);
            } catch (e) {
                alert(e.response.data.message);
            }

        },*/

        async handleSubmit() {

            // console.log(this.getForm());

            try {
                const {data} = await postsService().create(this.getForm());

                this.$emit('onPostCreated', data);
            } catch (e) {
                alert(e.response.data.message);
            }
        },

        clearForm() {
            this.$refs.title.value = '';
            this.$refs.content.innerHTML = '';
            //this.$refs.is_visible.checked = false;
            this.files = [];
        }
    }
}
</script>

<style scoped>

.ml-10px {
    margin-left: 10px;
}

.min-h-200px {
    min-height: 200px;
}

.min-h-100px {
    min-height: 100px;
}

.list-group-item {
    font-size: 0.9rem;
}

</style>
