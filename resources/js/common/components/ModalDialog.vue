<template>
    <div class="modal fade" data-bs-backdrop="static" :id="id" tabindex="-1" :aria-labelledby="labelledById" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" :id="labelledById">{{ title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot />
                </div>
                <div class="modal-footer">
                    <slot name="footer"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalDialog",
    props: {
        title: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        },
        size: {
            type: String
        }
    },

    computed: {
        labelledById() {
            return this.id + 'Label';
        }
    }
}
</script>

<style scoped>

</style>
