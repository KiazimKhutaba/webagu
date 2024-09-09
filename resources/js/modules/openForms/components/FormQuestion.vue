<template>
    <div class="card my-3 shadow-sm QuestionForm">

<!--        <div class="text-muted my-bg-gray px-4 py-1">
            <pre>{{ JSON.stringify($store.state['openFormStores'].questions, null, 2) }}</pre>
        </div>-->

        <div class="card-header bg-primary-subtle py-3">

            <div class="row">

                <div class="col-md-7">
                    <textarea class="form-control" placeholder="Введите вопрос" v-model="title" style="resize: none"></textarea>
                </div>

                <div class="col-md-2" title="Варианты ответа">
                    <select ref="type" class="form-select form-select-sm" v-model="variant_type">
                        <option value="radio">Один</option>
                        <option value="checkbox">Несколько</option>
                        <option value="text">Текст</option>
                        <option value="image">Изображения</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" :id="'of_desc_open' + qid" v-model="has_description">
                        <label class="form-check-label undefined" :for="'of_desc_open' + qid" style="color: rgb(108, 117, 125); cursor: pointer;">
                            Описание
                        </label>
                    </div>
                </div>

                <div class="col-md-1">
                    <file-upload :id="`file_upload_${qid}`" caption="Изображение" accept="image/*" @on-upload="onQuestionImageUpload" />
                </div>

                <div class="col-md-1 text-end">
                    <button class="btn btn-sm btn-primary" title="Добавить вариант" @click="addOption" :disabled="isOptionAddButtonActive">+</button>
                </div>

            </div>


            <div class="row mt-2" v-if="has_description">
                <div class="col-md-12">
                    <div class="mt-2">
                        <textarea class="form-control" placeholder="Введите описание вопроса" v-model="description" style="resize: none"></textarea>
                    </div>
                </div>
            </div>

        </div>


        <div class="card-body">

            <div class="text-center py-4" v-show="image">

                <div class="pb-4">
                    <button class="btn btn-sm btn-outline-danger" @click="removeImage">
                        Удалить изображение
                    </button>
                </div>


                <div>
                    <img
                        :src="image"
                        alt="изображение для вопроса"
                        class="img-fluid"
                    />
                </div>

            </div>


            <question-type-reply :type="variant_type">
                <div class="card-text pt-2" v-if="options?.length > 0">
                    <question-option
                        v-for="option in options"
                        :key="option.id"
                        :id="option.id"
                        :question-id="option.question_id"
                        :control-type="variant_type"
                        :name="option.question_id.split('-')[0]"
                        :is-checked="option.is_checked"
                        v-model:text="option.text"
                        @on-remove="onAnswerRemove"
                        @on-selected="onOptionSelected"
                        @on-enter-key="addOption"
                        @on-paste="onPasteToOptions"
                    />
                </div>
            </question-type-reply>

        </div>

        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-8 pt-1">
                    <div class="form-check">
                        <label class="text-muted mr-1" style="cursor: pointer;">Баллы:</label>
                        <input type="number" min="0" max="20" v-model.number="points"
                               class="wa-bg-color"
                               style="border: 0; text-align: center; background-color: rgb(251, 251, 251);">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <div class="mr-4" style="border-right: 1px solid rgb(170, 170, 170);">
                        <span><icon-copy/></span>
                        <span @click="onQuestionRemove"><icon-trash/></span>
                    </div>
                    <div class="form-check d-flex align-items-center ms-3">
                        <input class="form-check-input" type="checkbox" :id="`question.required__${qid}`" v-model="required">
                        <label class="form-check-label ps-2 pt-1" :for="`question.required__${qid}`"
                               style="color: rgb(108, 117, 125); cursor: pointer;">Обязательный</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FileUpload from "@/common/components/FileUpload.vue";
import QuestionTypeReply from "./QuestionTypeReply.vue";
import QuestionOption from "./QuestionOption.vue";
import IconCopy from "./icons/IconCopy.vue";
import IconTrash from "./icons/IconTrash.vue";
import IconClear from "./icons/IconClear.vue";
import IconRemove from "./icons/IconRemove.vue";
import {v4} from "uuid";
import {mapGetters, mapMutations} from "vuex";
import {buildOption} from "../OpenFormStore.js";

export default {
    name: "FormQuestion",
    components: {IconRemove, IconClear, IconTrash, IconCopy, QuestionOption, QuestionTypeReply, FileUpload},
    props: {
        qid: {
            type: String,
        },
        quizId: {
            type: String
        }
    },

    emits: ['onChange', 'onClick', 'onEnterKeyPress', 'onFormCopy', 'onQuestionRemove'],

    computed: {

        ...mapGetters('openFormStore', {
            quiz_question: 'question',
            question_options: 'options',
            question_option: 'option',
        }),

        question() {
            return this.quiz_question(this.qid);
        },

        options() {
            return this.question_options(this.qid);
        },

        title: {
            get() {
                return this.question?.title;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, title: value });
            }
        },

        image: {
            get() {
                return this.question?.image;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, image: value });
            }
        },

        variant_type: {
            get() {
                return this.question?.variant_type;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, variant_type: value });
                this.SET_OPTIONS_TYPE({ quiz_id: this.quizId, question_id: this.question.id, control_type: value });
                this.SET_OPTIONS_CHECKED({ question_id: this.question.id, is_checked: false });
            }
        },

        description: {
            get() {
                return this.question?.description;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, description: value });
            }
        },

        has_description: {
            get() {
                return this.question?.has_description;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, has_description: value });
            }
        },

        points: {
            get() {
                return this.question?.points;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, points: value });
            }
        },

        required: {
            get() {
                return this.question?.required;
            },
            set(value) {
                this.UPDATE_QUESTION({ ...this.question, required: value });
            }
        },

        isOptionAddButtonActive() {
            return !['radio', 'checkbox'].includes(this.variant_type);
        }
    },

    methods: {

        ...mapMutations('openFormStore', [
            'UPDATE_QUESTION', 'UPDATE_OPTION', 'ADD_OPTION', 'ADD_OPTIONS',
            'REMOVE_OPTION', 'SET_OPTIONS_TYPE', 'SET_OPTIONS_CHECKED'
        ]),

        /**
         *
         * @param text {string}
         * @param option_id
         */
        onPasteToOptions(text, option_id) {
            const lines = text.split('\n').map(line => line.replace(/[;.\n\-]/, ''));
            const options = lines.map(text => buildOption(v4(), this.qid, this.quizId, text, false, this.variant_type));

            this.REMOVE_OPTION(option_id);
            this.ADD_OPTIONS(options);
        },

        onQuestionImageUpload(image) {
            this.image = `/upload/${image.generated_name}`;
        },

        removeImage() {
            this.image = '';
        },

        addOption() {
            this.ADD_OPTION(buildOption(v4(), this.qid, this.quizId, '', false, this.variant_type));

            // Todo: focus on newly created element
            /*const el = document.querySelector(`#${variant.id}`);
            console.log(el)*/
            //this.$refs[variant.id].focus();
        },

        onAnswerRemove(id) {
            this.REMOVE_OPTION(id);
        },

        onOptionSelected({ id, checked, type }) {

            const option = this.options.find(o => o.id === id);

            // Todo: maybe this logic should be in store?
            if (type === 'radio') {
                this.SET_OPTIONS_CHECKED({ question_id: this.question.id, is_checked: false });
                this.UPDATE_OPTION({ ...option, is_checked: checked });
                return;
            }

            if (type === 'checkbox') {
                this.UPDATE_OPTION({ ...option, is_checked: checked });
            }
        },

        onQuestionRemove() {
            this.$emit('onQuestionRemove', this.qid);
        }
    }
}
</script>

<style scoped>
.bi-x {
    font-size: 1.3rem;
}

.wa-bg-color {
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}
</style>
