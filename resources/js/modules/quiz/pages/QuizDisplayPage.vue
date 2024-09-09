<template>
    <dashboard-wrapper>
        <page-header :caption="`#[${quiz.id}] ${quiz?.title}`">
            <button class="btn btn-sm btn-outline-primary" @click="goBack">Вернуться</button>
        </page-header>

        <template v-if="quiz_not_accessible">
            <h5>Ваша заявка на доступ к тесту принята, но еще не одобрена</h5>
        </template>

        <div v-else class="pb-4">

            <button v-if="is_training_quiz" :disabled="loading" class="btn btn-sm btn-outline-primary mb-3"
                    @click="regenerate">
                <span v-if="loading" class="d-flex align-items-center">
                    <span aria-hidden="true" class="spinner-border spinner-border-sm me-2"></span>
                    <span role="status">Ждем вопросы...</span>
                </span>
                <span v-else>
                    Поменять вопросы
                </span>
            </button>

            <q-loading :status="loading">

                <p class="alert alert-success d-flex align-items-center" v-if="passed_quiz?.status === PassedQuizStatus.OnReview">
                    <span><bi-info-circle/></span>
                    <span class="ms-2">
                        Ваш тест принят и ожидает проверки
                    </span>
                </p>

                <template v-if="is_midterm_quiz">

                    <midterm-quiz :passed_quiz="passed_quiz"
                                  :questions="questions"
                                  :questions-reply-files="questions_reply_files"
                                  :tasks="tasks"
                                  :tasks-reply-files="tasks_reply_files"
                                  @on-reply-file-upload="onReplyFileUpload"
                                  @on-reply-file-remove="onReplyFileRemove"
                    />

                </template>

                <template v-else-if="is_training_quiz">
                    <training-quiz :questions="questions" :quiz="quiz" :tasks="tasks"/>
                </template>

                <template v-else>
                    <seminar-quiz :passed_quiz="passed_quiz"
                                  :questions="questions"
                                  :questions-reply-files="questions_reply_files"
                                  :quizzes="quizzes"
                                  :quizzes-reply-files="quizzes_reply_files"
                                  :tasks="tasks"
                                  :tasks-reply-files="tasks_reply_files"
                                  @on-reply-file-upload="onReplyFileUpload"
                                  @on-reply-file-remove="onReplyFileRemove"
                    />
                </template>

                <button v-if="is_send_on_review_button_available" class="btn btn-sm btn-warning w-100 mt-3"
                        @click="sendOnReview">
                    Отправить на проверку
                </button>

            </q-loading>
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import QLoading from "@/common/components/QLoading.vue";
import FileUpload from "@/common/components/FileUpload.vue";
import {FileableType} from "@/common/enums/FileableType.js";
import RemoveIcon from "@/common/components/icons/RemoveIcon.vue";
import ReplyByFileCard from "@/modules/quiz/components/ReplyByFileCard.vue";
import {mapGetters} from "vuex";
import {QuizType} from "@/modules/quiz/utils/QuizType.js";
import TrainingQuiz from "@/modules/quiz/components/TrainingQuiz.vue";
import MidtermQuiz from "@/modules/quiz/components/MidtermQuiz.vue";
import SeminarQuiz from "@/modules/quiz/components/SeminarQuiz.vue";
import {PassedQuizStatus} from "@/modules/quiz/enums/PassedQuizStatus.js";
import PassedQuizPage from "@/modules/quiz/pages/PassedQuizPage.vue";
import BiInfoCircle from "@/common/components/icons/BiInfoCircle.vue";

export default {
    name: "QuizDisplayPage",
    components: {
        BiInfoCircle,
        SeminarQuiz,
        MidtermQuiz,
        TrainingQuiz, ReplyByFileCard, RemoveIcon, FileUpload, QLoading, PageHeader, DashboardWrapper
    },

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
                updated_at: ''
            },
            // questions and tasks associated with this quiz
            items: [],
            loading: false,
            questions: [],
            tasks: [],
            quizzes: [],
            page_switch_counter: 0,
            passed_quiz: {
                id: 0
            },
            reply_files: [],
        }
    },

    counter: 0,

    created() {
        /*window.addEventListener('beforeunload', (e) => {
            // Cancel the event as stated by the standard.
            e.preventDefault();
            // Chrome requires returnValue to be set.
            e.returnValue = '';

            //alert('Leave page?');
        });*/

        /*document.addEventListener('visibilitychange',  () => {
            if (document.visibilityState === 'hidden') {
                // console.log('Leave page');

                this.page_switch_counter++;

                if(this.page_switch_counter > 6) {
                    alert('Вы кикнуты!');
                }
                //console.log(this.page_switch_counter);
                //logsService().create({ message: 'page switched' });
                //navigator.sendBeacon('/api/logs', { hello: 1});
            }
        });*/
    },

    computed: {
        PassedQuizStatus() {
            return PassedQuizStatus
        },
        PassedQuizPage() {
            return PassedQuizPage
        },
        ...mapGetters('quizStore', [
            'get_questions_reply_files',
            'get_tasks_reply_files',
            'get_quizzes_reply_files',
            'get_quiz_replies',
        ]),

        questions_reply_files() {
            return this.reply_files?.filter(f => f.fileable_type === FileableType.QuizQuestion);
            // return this.get_questions_reply_files(this.passed_quiz.id);
        },

        tasks_reply_files() {
            return this.reply_files?.filter(f => f.fileable_type === FileableType.QuizTask);
            // return this.get_tasks_reply_files(this.passed_quiz.id);
        },

        quizzes_reply_files() {
            return this.reply_files?.filter(f => f.fileable_type === FileableType.QuizQuiz);
            // return this.get_quizzes_reply_files(this.passed_quiz.id);
        },

        FileableType() {
            return FileableType;
        },

        quiz_not_accessible() {
            if (this.quiz?.can_regenerate) return false;
            return this.is_student() && this.quiz?.access_granted === false;
        },

        is_training_quiz() {
            return this.quiz?.type === QuizType.Training;
        },

        is_seminar_quiz() {
            return this.quiz?.type === QuizType.Seminar;
        },

        is_midterm_quiz() {
            return this.quiz?.type === QuizType.Midterm;
        },

        is_send_on_review_button_available() {
            return [QuizType.Seminar, QuizType.Midterm].includes(this.quiz?.type)
                && (this.passed_quiz?.status === PassedQuizStatus.Waiting);
        }
    },

    async mounted() {
        await this.loadQuiz(this.$route.params.id);
    },


    methods: {

        onReplyFileUpload(file) {
            this.reply_files = [...this.reply_files, file];
        },

        onReplyFileRemove({id, generated_name}) {
            quizService()
                .removeReplyFile(id)
                .then(() => {
                    this.reply_files = this.reply_files.filter(file => file.generated_name !== generated_name);
                })
                .catch(err => console.error(err))
            ;
        },

        async getQuiz(quiz_uuid) {

            try {
                this.loading = true;
                const {data} = await quizService().getByUuid(quiz_uuid);
                return data;
            } catch (e) {
                console.error(e);
                alert(e);
            } finally {
                this.loading = false;
            }
        },

        async regenerate() {
            await this.loadQuiz(this.$route.params.id);
        },


        goBack() {
            this.$router.go(-1);
        },


        async loadQuiz(quiz_uuid) {
            const data = await this.getQuiz(quiz_uuid);
            this.quiz = data.quiz;
            this.passed_quiz = data.passed_quiz;
            this.questions = data.items?.questions;
            this.tasks = data.items?.tasks;
            this.quizzes = data.items?.quizzes;
            this.reply_files = data?.reply_files;
        },

        sendOnReview() {
            quizService()
                .sendOnReview(this.passed_quiz.id)
                .then(() => {
                    alert('Ваш тест принят и ожидает проверки. Спасибо!');
                    this.redirect_to('quizzes');
                })
            ;
        }
    }
}
</script>

<style scoped>
[data-bs-theme="dark"] li span {
    color: white !important;
}

/*.wa-text-color > * {
    color: #f0f !important;
}*/

[data-bs-theme="dark"] .wa-text-color > * {
    color: #fff !important;
}
</style>
