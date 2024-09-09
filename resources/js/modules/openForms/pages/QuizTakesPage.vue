<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption" />

        <div class="mx-1">
            <q-table :cols="cols" :rows="quizzes">
                <template #item="{item}">
                    <td>{{ item?.examinee.full_name}}</td>
                    <td>{{ item.quiz.title }}</td>
                    <td>{{ department_translate(item?.examinee.department) }}</td>
<!--                    <td>
                        <select class="form-select form-select-sm">
                            <option value="1" v-for="theme_title in item.theme_names">{{ theme_title }}</option>
                        </select>
                    </td>-->
                    <td>{{ dt_format2(item.created_at) }}</td>
<!--                    <td>{{ item.pass_count }}</td>-->
                    <td>
                        <router-link :to="url('openforms.quiztake', { id: item.id })">Посмотреть</router-link>
                    </td>
                </template>
            </q-table>
        </div>

        <pagination :pagination="pagination" />

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import QTable from "@/common/components/QTable.vue";
import Pagination from "@/common/components/Pagination.vue";
import {formService} from "@/modules/openForms/api/index.js";

export default {
    name: "PassedQuizzesPage",
    components: {Pagination, QTable, PageHeader, DashboardWrapper},
    data: () => ({
        quizzes: [],
        cols: {
            student_id: "ФИО Студента",
            quiz_title: "Название теста",
            department: 'Направление',
            //quiz_themes: "Темы теста",
            created_at: "Сдан",
            //pass_count: "Кол-во",
            actions: "Действия",
        },
        pagination: null,
    }),
    async mounted() {
        const { data } = await formService().getPassedQuizzes(this.$route.query);
        //console.log(data);
        this.quizzes = data.data;
        this.pagination = data;
    }
}
</script>

<style scoped>

</style>
