<script>
import RemoveIcon from "./icons/RemoveIcon.vue";
import Magnifier from "./Magnifier.vue";

export default {
    name: "FilesList",
    components: {Magnifier, RemoveIcon},

    emits: ['onImageClick', 'onImageRemove'],

    props: {
        list: {
            type: Array,
            required: true
        },
        targetModal: {
            type: String,
            required: true
        }
    },

    methods: {
        onImageClick(e) {
            if (e.target instanceof HTMLImageElement) {
                this.$emit('onImageClick', {selected: e.target.dataset.name, files: this.list});
            }
        }
    }
}
</script>

<template>
    <div class="col me-3" @click="onImageClick">
        <div class="d-inline-block position-relative me-2" v-for="(file, counter) in list">
            <img class="img-fluid img-thumbnail image:w100:px me-2"
                 alt="Изображение"
                 v-if="is_image(file)"
                 data-bs-toggle="modal"
                 :data-bs-target="targetModal"
                 :data-name="file.generated_name"
                 :src="upload_path(file)"
                 :key="counter"
            />
            <a v-else :href="upload_path(file)" target="_blank">
                <img src="/images/file-type-01.png" class="img-fluid img-thumbnail image:w100:px me-2" alt="task file" />
            </a>
        </div>
    </div>
</template>

<style scoped>

</style>
