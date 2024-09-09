<template>
    <div class="row pt-3 pb-2 mb-4 mx-1 border-bottom align-items-center g-0">
        <div class="col-md-8">
            <h3 class="h3">{{ caption }}</h3>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: "PageHeader",
    props: {
        caption: {
            type: String,
            required: true,
            default: 'No title'
        }
    },
    mounted() {
        document.title = this.caption;
    },
    watch: {
        caption(newValue) {
            document.title = newValue;
        }
    }
}
</script>

<style scoped>

</style>
