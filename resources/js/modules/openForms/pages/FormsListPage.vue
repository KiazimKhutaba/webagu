<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <router-link class="btn btn-sm btn-outline-primary" :to="$router.resolve({ name: 'openforms.formcreate' })">
                Новая форма
            </router-link>
        </page-header>

        <div class="mx-1">
            <q-loading :status="loading">
                <q-table :cols="cols" :rows="quizzes">
                    <template #item="{item}">
                        <td>{{ item.title }}</td>
                        <td>{{ item.lecture_id }}</td>
                        <td>{{ item.duration }}</td>
                        <td>{{ item.shuffle_questions }}</td>
                        <td>{{ dt_format(item.created_at) }}</td>
                        <td>
                            <router-link :to="`/openforms/form/${item.id}`">Смотреть</router-link>
                        </td>
                    </template>
                </q-table>

<!--                <div class="row">
                    <pagination :pagination="pagination" />
                </div>-->
            </q-loading>
        </div>

    </dashboard-wrapper>
</template>

<script>
import PageHeader from "@/common/components/PageHeader.vue";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import FormQuestion from "../components/FormQuestion.vue";
import {formService} from "../api/index.js";
import QLoading from "@/common/components/QLoading.vue";
import QTable from "@/common/components/QTable.vue";

export default {
    name: "QuizCreationPage",
    components: {QTable, QLoading, FormQuestion, DashboardWrapper, PageHeader},
    data: () => ({
        isCreationFormOpen: false,
        quizzes: [],
        loading: false,
        cols: {
            title: 'Название',
            lecture_id: 'Лекция',
            duration: 'Продолжительность',
            //is_available: 'Доступен',
            shuffle_questions: 'Перемешивать вопросы',
            created_at: 'Создан',
            actions: 'Действия'
        },
    }),

    async mounted() {
        try {
            this.loading = true;
            const { data } = await formService().getAll();
            this.quizzes = data;
            // this.pagination = data;
        }
        catch (e) {
            alert(e);
        }
        finally {
            this.loading = false;
        }
    },

    computed: {
        toggleFormButtonLabel() {
            return this.isCreationFormOpen ? 'Закрыть' : 'Добавить форму'
        }
    },

    methods: {
        toggleForm() {
            this.isCreationFormOpen = !this.isCreationFormOpen;
        }
    }
}
</script>

<style scoped>

</style>
