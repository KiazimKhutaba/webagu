<script>
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";
import FileUpload from "@/common/components/FileUpload.vue";
import {FileableType} from "@/common/enums/FileableType.js";
import FileChooser from "@/common/components/FileChooser.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import {QuizFileableType} from "@/modules/quiz/enums/QuizFileableType.js";

export default {
    name: "ReplyByFileCard",
    emits: ['onFileAdd', 'onFileRemove'],
    props: {
        id: {
          type: String,
          required: true,
        },
        label: {
            type: String,
            required: false,
            default: 'Добавить файл'
        },
        fileableId: {
          type: Number,
          required: true,
        },
        fileableType: {
            type: String,
            validator(value) {
                return QuizFileableType.values().includes(value);
            }
        },
        files: {
            type: Array,
            required: true,
        },
        fileChooserAvailable: {
            type: Boolean,
            required: false,
            default: true
        }
    },
    components: {FileChooser, FileUpload, RemoveIcon},

    data: () => ({
       //files: []
    }),

    methods: {
        async onReplyFileSelected(file) {

            const formData = new FormData();
            formData.set('file', file);
            formData.set('fileable_id', this.fileableId?.toString());
            formData.set('fileable_type', this.fileableType);

            const {data} = await quizService().addReplyFile(formData, _ => {});

            this.$emit('onFileAdd', data);
        },

        onReplyFileClick() {

        },

        onReplyFileRemove(e) {
            const id = e.target.dataset.fileId;
            const generated_name = e.target.dataset.fileName;
            const fileable_type = e.target.dataset.fileableType;

            this.$emit('onFileRemove', {id, generated_name,fileable_type});

        }
    }
}
</script>

<template>
    <div class="card mt-3 bg-transparent student-reply">
        <div class="card-header" v-if="fileChooserAvailable">
            <file-chooser
                :id="id"
                :fileable-type="fileableType"
                :caption="label"
                accept="image/*"
                @on-file-choose="onReplyFileSelected"
            />
        </div>
        <div class="card-body" v-if="files?.length > 0">
            <div class="row gap-2">
                <div class="col me-3" @click="onReplyFileClick">
                    <div v-for="(file, counter) in files" :data-name="file.generated_name"
                         class="d-inline-block position-relative me-2">
                        <remove-icon class="text-danger cursor-pointer position-absolute remove-icon"
                                     v-if="fileChooserAvailable"
                                     :data-file-id="file.id"
                                     :data-file-name="file.generated_name"
                                     :data-fileable-type="file.fileable_type"
                                     @click="onReplyFileRemove"
                        />
                        <img class="img-fluid img-thumbnail image:w100:px me-2"
                             :alt="file.generated_name"
                             data-bs-toggle="modal"
                             data-bs-target="#taskImageModal22"
                             :data-name="file.generated_name"
                             :src="upload_path(file.generated_name)"
                             :key="counter"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
