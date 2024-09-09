<template>
    <choose-files-dialog target-id="booksListDialog" @on-files-added="addFiles" />

    <form method="POST" enctype="multipart/form-data" @submit.prevent="handleSubmit" class="mb-3">

        <div class="my-3">
            <button type="submit" class="btn btn-sm btn-outline-success">{{ isEditingMode ? 'Обновить' : 'Добавить' }}</button>
            <input type="button" class="btn btn-sm btn-outline-warning ml-10px" @click="clearForm" value="Очистить форму"/>
            <input type="button" class="btn btn-sm btn-outline-danger ml-10px me-2" @click="" value="Удалить" v-if="true"/>

            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#booksListDialog">
                Добавить файл
            </button>
        </div>

        <div class="my-3">
            <label for="title" class="form-label fw-bold">Название</label>
            <input type="text" class="form-control" id="title" aria-describedby="title" ref="title"/>
        </div>

        <div class="mb-3" v-if="hasFiles">
            <div class="form-label fw-bold">Файлы</div>
            <ul class="list-group">
                <li class="list-group-item" v-for="file in files" :key="file.generated_name">
                    <div class="d-flex justify-content-between">
                        <div>{{ file.original_name }}</div>
                        <div class="cursor-pointer px-2 text-danger bi-x-lg" @click="removeFile" :data-file-id="file.id"></div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="mb-3">
            <label for="excerpt" class="form-label fw-bold">Короткое вступление</label>
            <div class="form-control min-h-100px" contenteditable="true" ref="excerpt"></div>
        </div>

        <div class="mb-3 d-none">
            <label for="content" class="form-label fw-bold">Текст</label>
            <div class="form-control min-h-200px" contenteditable="true" ref="content"></div>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label fw-bold">Текст</label>
            <ckeditor-custom v-model="editor_content" :bearer-token="bearerToken" :csrf-token="csrfToken" />
        </div>

        <div class="form-check mb-3 mr-sm-2">
            <input class="form-check-input" type="checkbox" id="openForViewing" ref="is_visible">
            <label class="form-check-label" for="openForViewing">
                Материал открыт для просмотра
            </label>
        </div>

    </form>
</template>

<script>
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";
import {filesService} from "@/modules/files/api/FilesService.js";
import {ref_value, ref_value_set} from "@/utils/index.js";
import ChooseFilesDialog from "@/common/components/ChooseFilesDialog.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";
import FileUpload from "@/common/components/FileUpload.vue";
/*import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph, Undo, List } from 'ckeditor5';
import {Ckeditor} from "@ckeditor/ckeditor5-vue";*/

import 'ckeditor5/ckeditor5.css';
import CkeditorCustom from "@/common/components/ckeditor/CkeditorCustom.vue";
import store from "@/store/index.js";
import authStore from "@/modules/account/authStore.js";
import {getAccessToken} from "@/utils/axiosClient.js";

export default {
    name: "CreateLectureForm",
    components: {CkeditorCustom, ChooseFilesDialog, FileUpload, RemoveIcon},
    emits: ['created', 'remove'],
    props: {
        title: {
            type: String
        },
        content: {
            type: String
        },
        lecture: {
            type: Object,
            default: {}
        },
        isEditingMode: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            files: [],
            editor_content: ''
        }
    },
    mounted() {
        if(this.isEditingMode && this.lecture) {
            this.fillForm(this.lecture);
        }
    },
    computed: {

        hasFiles() {
            return this.files?.length > 0;
        },

        bearerToken() {
            return getAccessToken();
        },

        csrfToken() {
            return document.head.querySelector('meta[name="csrf-token"]').content;
        }
    },
    methods: {

        fillForm(lecture) {
            const value_set = ref_value_set(this);
            value_set('title', lecture.title);
            value_set('excerpt', lecture.excerpt);
            value_set('content', lecture.content);
            value_set('is_visible', lecture.is_visible);
            this.files = lecture.files;
        },

        getForm() {

            const value = ref_value(this);

            return {
                title: value('title'),
                excerpt: value('excerpt'),
                content: this.editor_content, //value('content'),
                is_visible: Number(value('is_visible')),
                files: [...this.files?.map(file => file.id)],
            };
        },

        async addFiles(files) {
            this.files = [...this.files, ...files];
        },

        async removeFile(e) {

            try {
                const fileId = e.target.dataset.fileId;
                await filesService().remove(fileId);

                this.files = this.files.filter(file => +file.id !== +fileId);
            }
            catch (e) {
                alert(e.response.data.message);
            }

        },

        async handleSubmit() {

            try {
                const res = await lecturesService().create(this.getForm());

                this.$emit('created', res.data);
                //console.log(res);
            }
            catch (e) {
                console.error(e);
                alert(e);
            }
        },

        clearForm() {
            this.$refs.title.value = '';
            this.$refs.excerpt.innerHTML = '';
            this.$refs.content.innerHTML = '';
            this.$refs.is_visible.checked = false;
            this.files = [];
        }
    },

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

label[for="file-update"] {
    cursor: context-menu;
    /* Style as you please, it will become the visible UI component. */
}

#upload-file {
    opacity: 0;
    position: absolute;
    z-index: -1;
}

.list-group-item {
    font-size: 0.9rem;
}

</style>
