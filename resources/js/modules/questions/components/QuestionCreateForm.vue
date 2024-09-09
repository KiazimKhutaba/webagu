<template>
    <form @submit.prevent="handleSubmit" class="mb-3" :data-question-id="question.id">
        <div class="my-3">
            <label for="title" class="form-label fw-bold">Вопрос</label>
            <input type="text" class="form-control" id="title" minlength="5" aria-describedby="title" ref="title" required />
        </div>

        <div class="mb-3">
            <label for="content" class="form-label fw-bold">Содержание</label>
            <div class="form-control min-h-150px" contenteditable="true" ref="content"></div>
        </div>

        <div v-show="questionTypeIsTask" class="mb-3">
            <label for="solution" class="form-label fw-bold">Решение</label>
            <div class="form-control min-h-150px" contenteditable="true" id="solution" ref="solution"></div>
        </div>

        <div class="row my-3">

            <div class="col-md-6">
                <label for="question_type" class="form-label fw-bold">Тип</label>
                <select class="form-select" id="question_type" aria-label="Question type" ref="type" required v-model="entityType">
                    <option value="" selected disabled>Выберите тип</option>
                    <option value="question" selected>Вопрос</option>
                    <option value="task">Задача</option>
                    <option value="quiz">Тест</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="question_theme" class="form-label fw-bold">Тема</label>
                <select class="form-select" id="question_theme" aria-label="Тема, к которой относится задача" ref="theme" required>
                    <option value="" selected disabled>Выберите тему</option>
                    <option :value="theme.id" v-for="theme in themes" :key="theme.id">{{ theme.title }}</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-sm btn-success">{{
                question.id ? 'Обновить' : 'Сохранить'
            }}
        </button>
        <input type="button" class="btn btn-sm btn-warning ml-10px" @click="clearForm" value="Очистить форму"/>
<!--        <input type="button" class="btn btn-sm btn-danger ml-10px" @click="removeQuestion" value="Удалить вопрос" v-if="question.id"/>-->
    </form>
</template>

<script>
import {v4 as uuidv4} from 'uuid';
import {ref_value, ref_value_set} from "@/utils/index.js";

export default {
    name: "QuestionCreateForm",
    emits: ['create', 'update', 'remove'],
    props: {
        title: {
            type: String
        },
        content: {
            type: String
        },
        question: {
            type: Object
        },
        themes: {
            type: Array,
            default: []
        },
    },

    data() {
        return {
            entityType: ''
        }
    },

    mounted() {

        // Todo: refactor
        if (this.question)
        {
            const field = ref_value_set(this);

            for (const key in this.question) {
                field(key, this.question[key]);
            }
        }
    },
    computed: {
        questionTypeIsTask() {
            return (this.question.type === 'task') || (this.entityType === 'task'); // || (this.entityType === 'quiz');
        }
    },
    methods: {

        handleSubmit() {

            const value = ref_value(this);

            const question = {
                title: value('title'),
                content: value('content'),
                type: value('type'),
                theme: value('theme'),
                solution: ''
            }


            if (this.questionTypeIsTask) {
                question['solution'] = value('solution');
            }


            if(this.question?.id) {
                this.$emit('update', {...question, id: this.question.id, uuid: this.question.uuid});
            }
            else {
                this.$emit('create', {...question, uuid: uuidv4()});
            }

            this.clearForm();
        },

        clearForm() {
            this.$refs.title.value = '';
            this.$refs.content.innerHTML = '';
            this.$refs.type.value = '';
            this.$refs.theme.value = '';
            this.$refs.solution.innerHTML = '';
        }
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

.min-h-150px {
    min-height: 150px;
}


</style>
