<template>
    <dashboard-wrapper>
        <page-header :caption="page_title">
            <button class="btn btn-sm btn-outline-primary" @click="goBack">Вернуться</button>
        </page-header>

        <div class="pb-4">
            <q-loading :status="loading">

                <ul class="list-group" v-if="questions?.length > 0" >
                    <li class="list-group-item" v-for="question in questions">
                        <h5 class="h5">[Тема {{ question.theme }}] {{ question.title }} [{{ question.is_visible ? '+' : '-' }}]</h5>
                    </li>
                </ul>

                <reply-files :files="questions_reply_files" />

                <hr v-show="tasks?.length > 0">

                <ul class="list-group" v-if="tasks?.length > 0">
                    <li class="list-group-item" v-for="task in tasks">
                        <h4 class="text-warning">[Тема {{ task.theme }}] {{ task.title }}</h4>
                        <br/>
                        <span v-html="task.content" class="wa-text-color"></span>
                    </li>
                </ul>

                <reply-files :files="tasks_reply_files" />

                <hr v-show="quizzes?.length > 0">

                <ul class="list-group" v-if="quizzes?.length > 0">
                    <li class="list-group-item" v-for="_quiz in quizzes">
                        <h4 class="text-warning">{{ _quiz.title }}</h4>
                        <br/>
                        <span v-html="_quiz.content"></span>
                    </li>
                </ul>

                <reply-files :files="quizzes_reply_files" />

            </q-loading>

        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import QLoading from "@/common/components/QLoading.vue";
import ReplyFiles from "@/modules/quiz/components/ReplyFiles.vue";
import {QuizFileableType} from "@/modules/quiz/enums/QuizFileableType.js";

export default {
    name: "PassedQuizPage",
    components: {ReplyFiles, QLoading, PageHeader, DashboardWrapper},

    data() {
        return {
            quiz: {
                id: -1,
                uuid: '',
                title: '',
                number_of_questions: 0,
                number_of_tasks: 0,
                themes: '',
                is_available: 0,
                created_at: '',
                updated_at: '',
                reply_files: [],
            },
            // questions and tasks associated with this quiz
            items: [],
            loading: false,
            questions: [],
            tasks: [],
            quizzes: [],
            student: {
                id: 0,
                firstname: '',
                lastname: ''
            }
        }
    },

    async mounted() {
        const data = await this.getQuiz();
        this.quiz = data.quiz;
        this.student = data.quiz.student;
        this.questions = data.items.question;
        this.tasks = data.items.task;
        this.quizzes = data.items.quiz;

        // console.log(data.quiz)
    },

    computed: {
        page_title() {
            return `${this.quiz?.quiz_title} от ${this.quiz?.student_lastname} ${this.quiz?.student_firstname}`;
        },

        questions_reply_files() {
            return this.quiz?.reply_files.filter(f => f.fileable_type === QuizFileableType.QuizQuestion);
        },

        tasks_reply_files() {
            return this.quiz?.reply_files.filter(f => f.fileable_type === QuizFileableType.QuizTask);
        },

        quizzes_reply_files() {
            return this.quiz?.reply_files.filter(f => f.fileable_type === QuizFileableType.QuizQuiz);
        }
    },

    methods: {

        async getQuiz() {
            const id = this.$route.params.id;
            const { data } = await quizService().getPassedQuiz(id);
            return data;
        },

        goBack() {
            this.$router.push({ name: 'quizzes.passed-list' });
        },
    }
}
</script>

<style scoped>

[data-bs-theme="dark"] .wa-text-color > * {
    color: #fff !important;
}

</style>
