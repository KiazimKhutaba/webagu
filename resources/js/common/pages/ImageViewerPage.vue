<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <router-link :to="referer" class="btn btn-sm btn-outline-primary">Вернуться</router-link>
        </page-header>

        <div class="mx-1 mb-3">
            <button class="btn btn-sm btn-success">Повернуть по часовой</button>
        </div>

        <div class="mx-1 mb-3 text-center">
            <img :src="image" class="img-fluid" alt="image" onerror="this.error=null;this.src='/images/no-image.png'" />
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "../components/DashboardWrapper.vue";
import PageHeader from "../components/PageHeader.vue";
export default {
    name: "ImageViewerPage",
    components: {PageHeader, DashboardWrapper},

    data: () => ({
        student_id: 0,
        task_id: 0,
        image: '',
        imageRotate: ''
    }),

    mounted() {
        this.image = '/upload/' + this.$route.params.image;
    },

    computed: {
        referer() {

           const route = this.$router.resolve({
               name: 'tasks.get',
               params: { id: this.$route.query?.task_id || 1 },
               query: this.$route.query
           });

           return route.fullPath;
        },
    },

    methods: {
        rotate90() {
            this.imageRotate90 = '';
        }
    }
}
</script>

<style scoped>
.rotated90 {
    transform: rotate(90deg);
}
</style>
