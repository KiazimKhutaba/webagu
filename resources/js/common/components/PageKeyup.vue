<template>
  <div></div>
</template>

<script>
// @see https://stackoverflow.com/a/53600988/19233386
export default {
    name: "PageKeyup",
    emits: ['keyup'],
    created() {
        const component = this;
        this.handler = function (e) {
            component.$emit('keyup', e);
        }
        window.addEventListener('keyup', this.handler);
    },
    beforeDestroy() {
        window.removeEventListener('keyup', this.handler);
    }
}
</script>

<style scoped>
div {
    display: none;
}
</style>
