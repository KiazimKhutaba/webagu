<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {openFormsService} from "@/modules/openForms/api/BaseFormService.js";
import TakeQuestion from "@/modules/openForms/components/TakeQuestion.vue";
import QuizTakeQuestionAnswer from "@/modules/openForms/components/QuizTakeQuestionAnswer.vue";
import ImageSlider from "@/common/components/slider/ImageSlider.vue";
import ModalDialog from "@/common/components/ModalDialog.vue";

export default {
    name: "QuizTakePage",
    components: {ModalDialog, ImageSlider, QuizTakeQuestionAnswer, TakeQuestion, PageHeader, DashboardWrapper},

    data: () => ({
        take: {},
        quiz: {},
        questions: [],
        options: [],
        answers: [],

        answer_images: [],
    }),

    async mounted() {

        const {data} = await openFormsService().getQuizTake(this.$route.params.id);
        this.take = data;
        this.quiz = data.quiz;
        this.questions = data.quiz?.questions || [];
        this.options = data?.options || [];
        this.answers = data.answers;
    },

    computed: {
        caption() {
            return `Форма от ${this.take?.examinee?.full_name}`;
        }
    },

    methods: {
        /**
         *
         * @param answers {Array}
         * @param question_id {string}
         * @private
         */
        _question_answers(answers, question_id) {
            return answers.filter(a => a.question_id === question_id)[0];
        },

        /**
         *
         * @param files {Array}
         */
        onAnswersFiles(files) {
            this.answer_images = files;
        }
    }
}
</script>

<template>
    <dashboard-wrapper>
        <page-header :caption="caption">
            <router-link class="btn btn-sm btn-outline-primary" :to="url('openforms.quiztakes')">
                Назад
            </router-link>
        </page-header>

        <div class="mx-1 mb-3">
            <div class="card mb-3">

                <div class="card-header fw-bold">{{ quiz.title }}</div>

                <div class="card-body">
                    <!--<div class="card-title">{{ quiz.title }}</div>-->
                    <div class="card-text text-secondary">{{ quiz.description }}</div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col"><span class="text-secondary">Продолжительность:</span> {{ quiz.duration }}
                            минут
                        </div>
                        <!--<div class="col"><span class="text-secondary">Доступен: </span>
                        {{ quiz.is_available ? 'Да' : 'Нет' }}
                        </div>-->
                    </div>
                </div>
            </div>
        </div>


        <modal-dialog id="answerImageModal" title="answer images">
            <image-slider :images="answer_images" />
        </modal-dialog>

        <quiz-take-question-answer
            v-for="q in questions"
            bs-target="#answerImageModal"
            :key="q.id"
            :question="q"
            :options="options"
            :answers="_question_answers(answers, q.id)"
            @on-answer-files="onAnswersFiles"
        />

    </dashboard-wrapper>
</template>

<style scoped>

</style>
