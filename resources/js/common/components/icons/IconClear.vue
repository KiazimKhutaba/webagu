<template>
    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
         xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd"
          d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
    </svg>
</template>

<script>
export default {
    name: "IconClear",
    props: ['style'],
    emits: ['onClick'],

    methods: {
        onClick(e) {
            this.$emit('onClick', e);
        }
    }
}
</script>

<style scoped>

svg {
    cursor: pointer;
    font-size: 1.3em;
    margin-right: 1.5rem;
}
</style>
