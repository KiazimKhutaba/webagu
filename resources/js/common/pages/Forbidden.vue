<template>
    <dashboard-wrapper>
        <page-header caption="Доступ запрещен" />
        <p>Доступ запрещен!</p>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "../components/DashboardWrapper.vue";
import PageHeader from "../components/PageHeader.vue";
export default {
    name: "Forbidden",
    components: {PageHeader, DashboardWrapper}
}
</script>

<style scoped>

</style>
