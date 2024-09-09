<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption" />

        <div class="mx-1">
            <h2>Hello, {{ $route.params.username }}</h2>
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
export default {
    name: "UserPublicPage",
    components: {PageHeader, DashboardWrapper}
}
</script>

<style scoped>

</style>
