<template>
    <div class="d-inline-block">
        <label :for="id" :class="['btn btn-sm', this.class]">
            {{ caption }}
            <progress max="100" :value="loadingProgress" v-show="loading"></progress>
        </label>
        <input type="file" name="file" :id="id" @change="uploadFile" :accept="this.accept" :disabled="disabled" />
    </div>
</template>

<script>
import {filesService} from "@/modules/files/api/FilesService.js";
import {FileableType} from "@/common/enums/FileableType.js";

export default {
    name: "FileUpload",
    emits: ['uploadFile', 'onUpload'],
    props: {
        // !!!Without this uploading is not working when many file upload components on a page
        id: {
            type: String,
            required: true,
        },
        caption: {
            type: String,
            required: true
        },
        accept: {
            type: String,
            required: false,
            default: '*/*'
        },
        'class': {
            type: String,
            default: 'btn-outline-primary'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        fileableType: {
          type: String,
          validator(value) {
              return FileableType.values().includes(value);
          }
        },
        dest: {
            type: String,
            validator(value) {
                return ['uploads', 'avatars'].includes(value);
            }
        }
    },
    data() {
      return {
          loading: false,
          loadingProgress: 0
      }
    },
    methods: {
        async uploadFile(e) {

            this.loading = true;

            const file = e.target.files[0];

            const formData = new FormData();
            formData.set('file', file);
            formData.set('dest', this.dest || 'uploads');
            formData.set('fileable_type', this.fileableType);

            try {
                const res = await filesService().uploadFile(formData, progress => this.loadingProgress = progress);

                const createdFile = {
                    id: res.data.id,
                    original_name: res.data.original_name,
                    generated_name: res.data.generated_name,
                    type: res.data.type,
                    size: res.data.size,
                    user: res.data.user,
                    created_at: res.data.created_at,
                    fileable_type: res.data.fileable_type || 'files.form'
                };

                this.loading = false;
                this.$emit('uploadFile', createdFile);
                this.$emit('onUpload', createdFile);

                //e.target.value = null;

            } catch (err) {
                e.target.value = null; // this is needed as after error files list is still contains value of file
                //console.log('file.upload', e.target.files);
                //alert(err.response.data.message || err.response.statusText);
            }
            finally {
                this.loading = false;
                this.loadingProgress = 0;
            }
        }
    }
}
</script>

<style scoped>

div {

}

.ml-10px {
    margin-left: 10px;
}

label[for="file-update"] {
    cursor: context-menu;
}

input[type='file'] {
    /* opacity: 0;*/
    display: none;
    position: absolute;
    z-index: -1;
}

progress {
    display: block;
    height: 10px;
}

</style>
