<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import QLoading from "@/common/components/QLoading.vue";
import ReplyByFileCard from "@/modules/quiz/components/ReplyByFileCard.vue";

export default {
    name: "TrainingQuiz",
    components: {ReplyByFileCard, QLoading, PageHeader, DashboardWrapper},

    props: {
        quiz: {
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
    }
}
</script>

<template>
    <div class="pb-4">
        <ul class="list-group" v-if="questions?.length > 0">
            <li class="list-group-item" v-for="question in questions">
                <h5 class="h5">#{{ question.id }} {{ question.title }}</h5>
                <!-- <span v-html="question.content"></span>-->
            </li>
        </ul>

        <hr v-show="tasks?.length > 0">

        <ul class="list-group" v-if="tasks?.length > 0">
            <li class="list-group-item" v-for="task in tasks">
                <h4 class="text-warning">{{ task.title }}</h4>
                <br/>
                <span v-html="task.content" class="wa-text-color"></span>
            </li>
        </ul>

        <hr v-show="quizzes?.length > 0">

        <ul class="list-group" v-if="quizzes?.length > 0">
            <li class="list-group-item" v-for="_quiz in quizzes">
                <h4 class="text-warning">{{ _quiz.title }}</h4>
                <br/>
                <span v-html="_quiz.content"></span>
            </li>
        </ul>
    </div>
</template>

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
