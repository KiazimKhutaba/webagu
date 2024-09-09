<template>
    <div class="tab-pane fade" :id="`nav-${name}`" role="tabpanel" :aria-labelledby="`nav-${name}-tab`" tabindex="0">
        <slot>...</slot>
    </div>
</template>

<script>
export default {
    name: "NavTabPane",
    props: {
        name: {
            type: String,
            required: false
        }
    },
}
</script>

<style scoped>

</style>
