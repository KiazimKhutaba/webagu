<template>
    <nav>
        <div class="nav nav-tabs" :id="id" role="tablist">
            <slot />
        </div>
    </nav>
</template>

<script>
export default {
    name: "NavTabs",
    props: {
        id: {
            type: String,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
