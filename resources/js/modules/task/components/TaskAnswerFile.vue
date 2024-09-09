<script>
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";

export default {
    name: "TaskAnswerFile",
    components: {RemoveIcon},

    emits: ['onReviewTaskFileRemove'],

    props: {
        id: {
            type: String,
            required: false,
        },
        file: {
            type: Object,
            required: true
        }
    },

    methods: {
        onReviewTaskFileRemove(e) {
            const image_name = e.target.closest('div').dataset.name;
            this.$emit('onReviewTaskFileRemove', {image_name});
        }
    }
}
</script>

<template>
    <span>
       <remove-icon class="text-danger cursor-pointer position-absolute remove-icon" @click="onReviewTaskFileRemove"/>
        <img :alt="file?.name"
             :data-bs-target="id"
             :data-name="file?.name"
             :src="file?.data"
             class="img-fluid img-thumbnail image64px me-2"
             data-bs-toggle="modal"
        />
    </span>

</template>

<style scoped>

</style>
