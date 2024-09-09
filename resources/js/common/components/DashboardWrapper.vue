<template>
    <app-header/>

    <div class="container-fluid App">
        <div class="row">
            <app-sidebar/>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div v-if="isLoading" class="pt-3 pb-2 mb-4">
                    <h3>Загрузка данных...</h3>
                </div>

                <slot v-else-if="!isLoading">
                    <div class="pt-3 pb-2 mb-4">
                        <h3 class="h3">Нет данных</h3>
                        <div>Для данной страницы нет данных, которые можно отобразить <b>({{ $route.fullPath }})</b></div>
                    </div>
                </slot>

            </main>
        </div>
    </div>
</template>

<script>
import AppHeader from "./AppHeader.vue";
import AppSidebar from "./AppSidebar.vue";

export default {
    name: "DashboardWrapper",
    components: {AppSidebar, AppHeader},
    props: {
        isLoading: {
            type: Boolean,
            default: false
        },
        hasContent: {
            type: Boolean,
            default: true
        }
    },
}
</script>

<style scoped>

.App {
    height: calc(100% - 55px);
}

.App > .row {
    height: 100%
}

@media screen and (max-width: 768px) {
    .App {
        height: auto;
    }

}

</style>
