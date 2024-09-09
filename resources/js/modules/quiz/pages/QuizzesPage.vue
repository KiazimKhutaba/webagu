<template>
    <dashboard-wrapper>
        <page-header :caption="pageCaption">
            <button class="btn btn-sm btn-outline-primary" @click="toggleQuizCreateForm" v-show="is_admin()">
                {{ isQuizCreateFormOpen ? 'Закрыть форму' : 'Добавить тест' }}
            </button>
        </page-header>

        <quiz-create-form v-if="isQuizCreateFormOpen" @on-quiz-data="onQuizData"/>

        <div class="table-responsive" v-else>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col" class="d-none d-md-table-cell">Вопросов</th>
                    <th scope="col" class="d-none d-md-table-cell">Задач</th>
                    <th scope="col" class="d-none d-md-table-cell" v-if="is_admin()">Темы</th>
                    <th scope="col">Действия</th>
                    <th scope="col" v-if="is_admin()">Заявки</th>
                    <th scope="col" v-if="is_admin()">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="cursor-pointer" v-for="(quiz, counter) in quizzes" @click="onRowClick"
                    :data-document-id="quiz.uuid">
                    <!--<th scope="row" class="d-none d-md-table-cell">{{ ++counter }}</th>-->
                    <th scope="row">{{ quiz.id }}</th>
                    <td>{{ quiz.title }}
                        <quiz-type-badge :type="quiz?.type"/>
                    </td>
                    <td class="d-none d-md-table-cell">{{ quiz.number_of_questions }}</td>
                    <td class="d-none d-md-table-cell">{{ quiz.number_of_tasks }}</td>
                    <td class="d-none d-md-table-cell" v-if="is_admin()">
                        <select class="form-select form-select-sm">
                            <option value="1" v-for="theme_title in quiz.theme_names">{{ theme_title }}</option>
                        </select>
                    </td>
                    <!--                    <td v-if="is_admin()">{{ isQuizAvailable(quiz) ? 'Да' : 'Нет' }}</td>-->
                    <td>
                        <router-link v-if="isQuizAvailable(quiz)" :to="'/quiz/' + quiz.uuid">
                            {{ quiz_actions_label(quiz) }}
                        </router-link>
                        <span v-else>x</span>
                    </td>
                    <td v-if="is_admin()">
                        <span v-if="quiz?.can_regenerate">-</span>
                        <router-link :to="url('quiz.access-requests', { id: quiz.uuid })" v-else>[+]</router-link>
                    </td>
                    <td v-if="is_admin()">
                        <a href="#" :data-document-id="quiz.uuid" data-action="status" :data-status="quiz.is_available">
                            {{ isQuizAvailable(quiz) ? 'Закрыть' : 'Открыть' }}
                        </a> |
                        <a href="#" :data-document-id="quiz.uuid" data-action="delete">Удалить</a>
                    </td>
                </tr>
                </tbody>
            </table>

            <pagination :pagination="pagination"/>
        </div>

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import QuizCreateForm from "../components/QuizCreateForm.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import Pagination from "@/common/components/Pagination.vue";
import QuizTypeBadge from "@/modules/quiz/components/QuizTypeBadge.vue";

export default {
    name: "QuizzesPage",
    components: {QuizTypeBadge, Pagination, QuizCreateForm, PageHeader, DashboardWrapper},

    data() {
        return {
            themes: [],
            quizzes: [],
            isQuizCreateFormOpen: false,
            students: [],
            pagination: null,
        };
    },

    async mounted() {
        const paginatedQuizzes = await this.getQuizzes();
        this.quizzes = paginatedQuizzes.data;
        this.pagination = paginatedQuizzes;
    },

    computed: {
        pageCaption() {

            if (this.isQuizCreateFormOpen) {
                return 'Новый тест';
            }

            return this.$route?.meta?.caption || 'No title';
        },

    },

    methods: {

        quiz_actions_label(quiz) {

            const request = quiz?.access_request

            if (this.is_student()) {

                if (request !== null) {
                    if (request.granted) {
                        return 'Начать';
                    } else {
                        return 'Заявка ⏳';
                    }
                }


                return 'Открыть';
            }

            return 'Смотреть';
        },

        async getQuizzes() {
            try {
                const {data} = await quizService().paginate(this.$route.query);
                return data;
            } catch (e) {
                console.error(e);
                return [];
            }
        },

        async onQuizData(quiz) {
            try {
                const {data: createdQuiz} = await quizService().create(quiz);
                // console.log(createdQuiz);
                this.quizzes = [createdQuiz, ...this.quizzes];
                this.isQuizCreateFormOpen = false;
            } catch (e) {
                console.error(e);
                alert(e);
            }
        },

        toggleQuizCreateForm() {
            this.isQuizCreateFormOpen = !this.isQuizCreateFormOpen;
        },

        isQuizAvailable(quiz) {
            return +quiz.is_available === 1;
        },


        async onRowClick(e) {
            if (e.target.tagName === 'A') {
                const action = e.target.dataset.action;

                switch (action) {
                    case 'status': {
                        //this.generatedQuizId = e.target.dataset.documentId;
                        const status = e.target.dataset.status === '1' ? '0' : '1';
                        const documentId = e.target.dataset.documentId;

                        //console.log(!!action)//alert(this.generatedQuizId);

                        // Todo: this is incorrect
                        await quizService().toggleStatus(documentId, status);

                        this.quizzes.forEach(quiz => quiz.uuid === documentId ? quiz.is_available = status : false);
                        //this.showRowData = true;
                        break;
                    }

                    case 'delete': {
                        const choice = confirm("Do you really want remove this item?");

                        if (choice) {
                            const documentId = e.target.dataset.documentId;

                            await quizService().remove(documentId);

                            this.quizzes = this.quizzes.filter(q => q.uuid !== documentId);
                        }

                    }
                }
            }
            //alert(e.target.closest('tr').dataset.documentId)
        }
    }
}
</script>

<style scoped>

</style>
