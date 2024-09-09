<script>
import FileChooser from "@/common/components/FileChooser.vue";
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";

export default {
    name: "ReplyFiles",
    components: {RemoveIcon, FileChooser},

    props: {
        files: {
            type: Array,
            required: false
        }
    }
}
</script>

<template>
    <div class="card mt-3 bg-transparent student-reply">
        <div class="card-body" v-if="files?.length > 0">
            <div class="row gap-2">
                <div class="col me-3" @click="onReplyFileClick">
                    <div v-for="file in files" :data-name="file.generated_name" class="d-inline-block position-relative me-2">
                        <img class="img-fluid img-thumbnail image:w100:px me-2"
                             :alt="file.generated_name"
                             data-bs-toggle="modal"
                             data-bs-target="#taskImageModal223"
                             :data-name="file.generated_name"
                             :src="upload_path(file.generated_name)"
                             :key="file.id"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
