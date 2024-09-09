<script>
import Magnifier from "./Magnifier.vue";

export default {
    name: "ImageModal",
    components: {Magnifier},
    props: {
        id: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        src: {
            type: String,
            required: true
        },
    },
}
</script>

<template>
<div class="modal fade" :id="id" tabindex="-1">
    <div class="modal-dialog modal-fullscreen modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <img class="img-fluid" :alt="title" :src="src"/>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>
