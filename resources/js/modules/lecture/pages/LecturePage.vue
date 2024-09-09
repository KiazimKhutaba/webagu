<template>
    <dashboard-wrapper :is-loading="isLoading" :has-content="hasContent">
        <page-header :caption="lecture.title">
            <button class="btn btn-sm btn-outline-primary" @click="goBack">Вернуться</button>
        </page-header>

        <div class="mb-3" v-if="hasFiles">
            <div class="form-label fw-bold">Файлы лекции</div>
            <div class="card">
                <div class="card-body">
                    <div v-for="(file, counter) in lecture.files" :key="file.generated_name">
                        {{ ++counter }}.&nbsp; <a :href="'/upload/' + file.generated_name">{{ file.original_name }}</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-3" v-show="hasTasks">
            <div class="fw-bold mb-3">Связанные задачи</div>
            <ul class="list-group">
                <li v-for="(task, counter) in lecture.tasks" class="list-group-item">
                    {{ ++counter }}. <router-link :to="`/tasks/${task.id}`" target="_blank">{{ task.title }}</router-link>
                </li>
            </ul>
        </div>

<!--        <div class="fw-bold mb-3">Короткое описание</div>-->
        <div class="mt-4" v-html="lecture.excerpt"></div>

        <hr class="fw-bold" />

<!--        <div class="fw-bold mb-3">Текст лекции</div>-->
        <div v-html="lecture.content" class="mb-5"></div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";


export default {
    name: "LecturePage",
    components: {PageHeader, DashboardWrapper},
    data() {
        return {
            isLoading: false,
            hasContent: false,
            lecture: {},
        }
    },
    computed: {
        hasFiles() {
            return this.lecture?.files?.length > 0;
        },

        hasTasks() {
            return this.lecture?.tasks?.length > 0;
        }
    },
    methods: {
        goBack() {
            this.$router.push({ name: 'dashboard' });
        },

        async getLecture() {

            this.isLoading = true;

            try {
                const {id} = this.$route.params;

                const {data} = await lecturesService().getById(id);
                this.lecture = data;
                this.hasContent = true;

                /*const res2 = await lecturesService.getTasks(id);
                this.tasks = [...res2.data];*/
            }
            catch (e) {

                if(e.response.status === 404) {
                    this.hasContent = false;
                }
            }
            finally {
                this.isLoading = false;
            }
        }
    },
    async mounted() {
        await this.getLecture();
    }
}
</script>

<style>
img {
    width: 100%;
    object-fit: cover;
    height: auto;
}

.image {
    text-align: center !important;
    max-width: 70% !important;
    margin: 0 auto;
}

@media screen and(max-width: 768px) {
    .image {
        max-width: 100% !important;
    }
}
</style>
