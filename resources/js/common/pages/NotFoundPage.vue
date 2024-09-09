<template>
    <dashboard-wrapper>
        <page-header caption="Страница не найдена" />
        <p>Запрашиваемая Вами страница не найдена (<b>{{ $route.path }}</b>)</p>
    </dashboard-wrapper>
</template>

<script>

import DashboardWrapper from "../components/DashboardWrapper.vue";
import PageHeader from "../components/PageHeader.vue";
export default {
  name: "NotFoundPage",
    components: {PageHeader, DashboardWrapper}
}
</script>

<style scoped>

</style>
