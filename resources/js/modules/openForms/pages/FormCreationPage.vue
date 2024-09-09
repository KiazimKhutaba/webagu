<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <router-link class="btn btn-sm btn-outline-primary" :to="$router.resolve({ name: 'openforms.formslist' })">
                Назад
            </router-link>
        </page-header>

        <div class="mx-1">

            <div class="my-3 button-group position-sticky">
                <button class="btn btn-sm btn-outline-primary" @click="addQuestion">Добавить вопрос</button>
                <button class="btn btn-sm btn-outline-warning" @click="onServerSave">Сохранить на сервер</button>
                <button class="btn btn-sm btn-outline-danger" @click="clearForm">Очистить</button>
            </div>

            <form-header />

            <template v-if="questions.length > 0">
                <form-question v-for="question in questions" :key="question.id" :quiz-id="quiz_id" :qid="question.id" @on-question-remove="onQuestionRemove" />
            </template>

            <div class="my-3 button-group">
                <button class="btn btn-sm btn-outline-primary" @click="addQuestion">Добавить вопрос</button>
            </div>

        </div>
    </dashboard-wrapper>
</template>

<script>
import FormHeader from "../components/FormHeader.vue";
import FormQuestion from "../components/FormQuestion.vue";
import {v4} from "uuid";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {mapGetters, mapMutations} from "vuex";
import {buildQuestion} from "../OpenFormStore.js";
import {toRaw} from "vue";
import {formService} from "../api/index.js";

export default {
    name: "FormCreationPage",
    components: {PageHeader, DashboardWrapper, FormQuestion, FormHeader},

    data: () => ({

    }),

    computed: {

        ...mapGetters('openFormStore', {
            questions: 'questions',
            quiz_id: 'quiz_id'
        }),
    },

    methods: {

        ...mapMutations('openFormStore', ['ADD_QUESTION', 'REMOVE_QUESTION', 'CLEAR_FORM']),

        addQuestion() {
            this.ADD_QUESTION(buildQuestion(v4(), this.quiz_id));

            let y = document.body.scrollHeight || document.documentElement.scrollHeight;
            window.scrollTo(0, y);
        },

        onQuestionRemove(qid) {
            this.REMOVE_QUESTION(qid);
        },

        clearForm() {
            this.CLEAR_FORM();
        },

        async onServerSave() {
            const store = {
                quiz: toRaw(this.$store.state['openFormStore'].quiz),
                questions: toRaw(this.$store.state['openFormStore'].questions),
                options: toRaw(this.$store.state['openFormStore'].options),
            };

            try {
                const { data } = await formService().save2(store);
                this.$router.push({ name: 'openforms.formslist' })
            }
            catch (e) {
                alert(e);
            }


            //console.log(toRaw(this.$store.state['openFormStores'].quiz));
        }
    }

}
</script>

<style scoped>
.button-group > button {
    margin-right: 10px;
    top: 100px;
}
</style>
