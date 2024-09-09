<template>
    <form @submit.prevent="handleSubmit" class="mb-3">
        <div class="my-3">
            <label for="title" class="form-label fw-bold mb-3">Вставьте вопросы в поле ниже</label>
            <input type="text" class="form-control mb-3" ref="massQuestionsCreationFormField" placeholder="Вставьте вопросы..." value="" @paste="onQuestionsPaste"
                   aria-describedby="question paste"/>
        </div>

        <div class="my-3 fw-bold">
            Вопросы
        </div>

        <div class="my-3" @click="onRemove">
            <div class="input-group mb-2" v-for="(question, i) in questions">
                <div class="input-group-text">
                    {{ counter(++i) }}
                </div>
                <input type="text" class="form-control" v-model="question.text" />
                <div class="input-group-text cursor-pointer remove-button" :data-id="question.id">
                    <icon-remove/>
                </div>
            </div>
        </div>

        <div class="row my-3">

            <div class="col">
                <label for="question_theme" class="form-label fw-bold mt-3">Тема</label>
                <select class="form-select" id="question_theme" aria-label="Тема, к которой относится задача" ref="selected_lecture" required>
                    <option value="" selected disabled>Выберите тему</option>
                    <option :value="lecture?.id" v-for="lecture in lectures" :key="lecture?.id">{{ lecture?.title }}</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
        <input type="button" class="btn btn-sm btn-warning ml-10px" @click="clearList" value="Очистить форму"/>
    </form>
</template>

<script>
import {v4} from 'uuid';
import IconRemove from "@/common/components/icons/IconRemove.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";

export default {
    name: "MassQuestionsAddForm",
    components: {IconRemove},
    emits: ['onData'],
    props: {

    },

    data() {
        return {
            entityType: '',
            lectures: [],
            questions: [],
            rawData: ''
        }
    },

    async mounted() {
        const {data} = await lecturesService().pluck(['id', 'title']);
        this.lectures = data;
    },
    computed: {

        /**
         * @see https://stackoverflow.com/a/14879700/19233386
         *
         * @return {number}
         * @private
         */
        _digitsLength() {
            return Math.log(this.questions.length) * Math.LOG10E + 1 | 0;
        },
    },
    methods: {


        buildQuestion(text) {
            return {
                id: v4(),
                text
            }
        },

        onRemove(e) {
            const id = e.target.closest('div.remove-button')?.dataset.id;
            if (id) {
                this.questions = this.questions.filter(q => q.id !== id);
            }
        },

        counter(num) {
            return num.toString().padStart(this._digitsLength, '0');
        },

        /**
         *
         * @param e {ClipboardEvent}
         */
        onQuestionsPaste(e) {

            /**
             * @param text {string}
             */
            const removeSymbols = text => text.replace(/[;.\n\-]/, '').trim();

            /**
             * @param text {string}
             * @return {boolean}
             */
            const removeEmptyValues = text => text.length !== 0;

            const removeDigitsFromBeginning = str => str.replace(/^\d+\.?\s*/, '');

            const text = e.clipboardData.getData('text/plain')?.trim();
            // const text = this.rawData;

            if (text.length) {
                const lines = text.split('\n').map(removeSymbols).filter(removeEmptyValues).map(removeDigitsFromBeginning);
                this.questions = [...this.questions, ...lines.map(this.buildQuestion)];
            }
        },

        handleSubmit() {

            // this.$refs.massQuestionsCreationFormField.value = '';

            if(0 === this.questions.length) {
                alert('Вы не добавили ни одного вопроса!');
                return;
            }

            const lecture_id = this.$refs.selected_lecture.value;

            const data = this.questions.map(q => ({
                title: q.text,
                theme: +lecture_id,
                type: 'question',
                content: '',
                solution: '',
                uuid: q.id
            }));

            this.$emit('onData', data);
            this.clearList();
        },

        clearList() {
            this.questions = [];
        },
    },

}
</script>

<style scoped>

img {
    width: 100% !important;
}

.ml-10px {
    margin-left: 10px;
}



</style>
