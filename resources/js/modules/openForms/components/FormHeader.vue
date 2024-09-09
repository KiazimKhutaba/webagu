<template>

    <div class="accordion mb-3 d-none" id="debugAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" style="font-size: 0.8rem" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Отладка
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#debugAccordion">
                <div class="accordion-body">
                    <div class="max-h-300px overflow-y-auto">
                        <pre>{{ JSON.stringify($store.state['openFormStore'], null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <label for="quiz.title" class="col-form-label text-muted">Введите название для теста</label>
                    <input type="text" class="form-control form-control-sm" id="quiz.title" v-model="title">
                </div>
                <div class="col-md-4">
                    <label for="quiz.lecture" class="col-form-label text-muted">Лекции</label>
                    <select class="form-select form-select-sm" id="quiz.lecture" v-model="lecture_id" required>
                        <option selected disabled value="0">Выберите лекцию</option>
                        <option :value="lecture.id" v-for="lecture in lectures">{{ lecture.title }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="quiz.description" class="col-form-label text-muted mt-2">
                        Введите описание для теста
                    </label>
                    <textarea class="form-control form-control-sm" rows="3" id="quiz.description"
                              v-model="description"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row pt-1 align-items-center">
                <div class="col-md-4">
                    <div class="form-check">
                        <label class="text-muted mr-1 cursor-pointer">Продолжительность (мин): </label>
                        <input type="number" min="0" max="60" class="wa-bg-color border-0 text-center bg-secondary-2"
                               v-model="duration">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="quiz.opened" :checked="is_available"
                               v-model="is_available">
                        <label class="wa-bg-color form-check-label bg-secondary-2 cursor-pointer" for="quiz.opened">Доступен</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="quiz.questions.shuffle"
                               :checked="shuffle_questions" v-model="shuffle_questions">
                        <label class="wa-bg-color form-check-label bg-secondary-2 cursor-pointer" for="quiz.questions.shuffle">Перемешивать
                            вопросы</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";
import {mapGetters, mapMutations} from "vuex";

export default {
    name: "FormHeader",

    data: () => ({
        lectures: []
    }),

    async mounted() {
        const {data} = await lecturesService().pluck(['id', 'title']);
        this.lectures = data;

        // console.log(this.$store.state['openFormStores'].quiz);
    },

    computed: {

        ...mapGetters('openFormStore', {
            quiz_title: 'title',
            quiz_lecture_id: 'lecture_id',
            quiz_description: 'description',
            quiz_duration: 'duration',
            quiz_is_available: 'is_available',
            quiz_shuffle_questions: 'shuffle_questions'
        }),

        title: {
            get() {
                return this.quiz_title;
            },
            set(value) {
                this.SET_TITLE(value);
            }
        },

        lecture_id: {
            get() {
                return this.quiz_lecture_id;
            },
            set(value) {
                this.SET_LECTURE_ID(value);
            }
        },

        description: {
            get() {
                return this.quiz_description;
            },
            set(value) {
                this.SET_DESCRIPTION(value);
            }
        },

        duration: {
            get() {
                return this.quiz_duration;
            },
            set(value) {
                this.SET_DURATION(value);
            }
        },

        is_available: {
            get() {
                return this.quiz_is_available;
            },
            set(value) {
                this.SET_IS_AVAILABLE(value);
            }
        },

        shuffle_questions: {
            get() {
                return this.quiz_shuffle_questions;
            },
            set(value) {
                this.SET_SHUFFLE_QUESTIONS(value);
            }
        }
    },

    methods: {
        ...mapMutations('openFormStore', [
            'SET_TITLE',
            'SET_LECTURE_ID',
            'SET_DESCRIPTION',
            'SET_DURATION',
            'SET_IS_AVAILABLE',
            'SET_SHUFFLE_QUESTIONS'
        ]),
    }

}
</script>

<style scoped>
.bg-secondary-2 {
    background-color: rgb(251, 251, 251);
}

.max-h-300px {
    max-height: 300px;
}

.wa-bg-color {
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}

</style>
