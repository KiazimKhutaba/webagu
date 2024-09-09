<script>
import ReplyByFileCard from "@/modules/quiz/components/ReplyByFileCard.vue";
import {QuizFileableType} from "@/modules/quiz/enums/QuizFileableType.js";
import {PassedQuizStatus} from "@/modules/quiz/enums/PassedQuizStatus.js";

export default {
    name: "SeminarQuiz",
    components: {ReplyByFileCard},

    emits: ['onReplyFileUpload', 'onReplyFileRemove'],

    props: {
        passed_quiz: {
            type: Object,
            required: true,
        },
        questions: {
            type: Array,
            required: true
        },
        tasks: {
            type: Array,
            required: true
        },
        quizzes: {
            type: Array,
            required: false
        },
        questionsReplyFiles: {
            type: Array,
            required: true
        },
        tasksReplyFiles: {
            type: Array,
            required: true
        },
        quizzesReplyFiles: {
            type: Array,
            required: true
        },
    },

    computed: {
        QuizFileableType() {
            return QuizFileableType
        },

        file_chooser_available() {
            return this.passed_quiz.status === PassedQuizStatus.Waiting;
        }
    },

    methods: {
        onReplyFileUpload(file) {
            this.$emit('onReplyFileUpload', file);
        },

        onReplyFileRemove(file) {
            this.$emit('onReplyFileRemove', file);
        }
    }
}
</script>

<template>
    <div>
        <ul class="list-group" v-if="questions?.length > 0">
            <li class="list-group-item" v-for="question in questions">
                <h5 class="h5">#{{ question.id }} {{ question.title }}</h5>
                <!-- <span v-html="question.content"></span>-->
            </li>
        </ul>

        <reply-by-file-card
            id="quiz_questions_reply"
            :file-chooser-available="file_chooser_available"
            :fileable-id="passed_quiz?.id"
            :fileable-type="QuizFileableType.QuizQuestion"
            :files="questionsReplyFiles"
            @on-file-add="onReplyFileUpload"
            @on-file-remove="onReplyFileRemove"
        />

        <hr v-show="tasks?.length > 0">

        <ul class="list-group" v-if="tasks?.length > 0">
            <li class="list-group-item" v-for="task in tasks">
                <h4 class="text-warning">{{ task.title }}</h4>
                <br/>
                <span v-html="task.content" class="wa-text-color"></span>
            </li>
        </ul>

        <reply-by-file-card
            id="quiz_tasks_reply"
            :file-chooser-available="file_chooser_available"
            :fileable-id="passed_quiz?.id"
            :fileable-type="QuizFileableType.QuizTask"
            :files="tasksReplyFiles"
            @on-file-add="onReplyFileUpload"
            @on-file-remove="onReplyFileRemove"
        />

        <hr v-show="quizzes?.length > 0">

        <ul class="list-group" v-if="quizzes?.length > 0">
            <li class="list-group-item" v-for="_quiz in quizzes">
                <h4 class="text-warning">{{ _quiz.title }}</h4>
                <br/>
                <span v-html="_quiz.content"></span>
            </li>
        </ul>

        <reply-by-file-card
            v-if="quizzes?.length > 0"
            id="quiz_quiz_reply"
            :file-chooser-available="file_chooser_available"
            :fileable-id="passed_quiz?.id"
            :fileable-type="QuizFileableType.QuizQuiz"
            :files="quizzesReplyFiles"
            @on-file-add="onReplyFileUpload"
            @on-file-remove="onReplyFileRemove"
        />

    </div>
</template>


<style scoped>

</style>
