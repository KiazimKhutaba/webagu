<template>
    <div class="card bg-primary-subtle mb-3">
        <!--        <div class="card-header overflow-y-auto" style="max-height: 200px">
        &lt;!&ndash;            <pre>{{
                            ['radio', 'checkbox'].includes(question.variant_type) ? selected : question.variant_type === 'text' ? text : images
                        }}</pre>&ndash;&gt;
        &lt;!&ndash;            <pre>{{ responses }}</pre>&ndash;&gt;
                </div>-->
        <div class="card-body">
            <div class="card-title row mb-3">
                <span class="col-md-10 fw-bold">{{ question.title }}</span>
                <span class="col-md-2 text-md-end"><span class="text-secondary">Баллов:</span> {{
                        question.points
                    }}</span>
            </div>

            <div class="card-subtitle text-secondary mb-3" v-if="question.has_description">
                {{ question.description }}
            </div>

            <div class="card-img text-center my-3" v-if="question.image">
                <img :src="question.image" class="img-fluid" :alt="question.title">
            </div>

            <div class="card-text mt-3">

                <div class="mb-3" v-if="question.variant_type === 'text'">
                    <textarea class="form-control" placeholder="Напишите Ваш ответ здесь..." v-model="text"
                              @change="onTextChanged"></textarea>
                </div>

                <div class="mb-5 text-center" v-else-if="question.variant_type === 'image'">
                    <file-upload caption="Загрузите изображение ответа" @on-upload="onImageUpload" accept="image/*" :id="question.id" />
                </div>

                <div v-if="images.length > 0">
                    <div class="row row-gap-2">
                        <div class="col-md-3 position-relative" v-for="generated_name in images">
                            <span class="bi bi-x-circle cursor-pointer image-remove-icon"
                                  :data-id="generated_name" @click="onImageRemove"></span>
                            <img class="img-thumbnail rounded-2" :src="'/upload/' + generated_name" alt="quiz take image"/>
                        </div>
                    </div>
                </div>

                <div class="form-check border-bottom border-1 border-primary py-2 px-4 d-flex align-items-center"
                     v-for="option in options" v-else>
                    <input class="form-check-input" :type="option.control_type" :name="option.question_id"
                           :value="option" :id="option.id" v-model="_selected" @change="onOptionSelected">
                    <label class="form-check-label cursor-pointer ms-2" :for="option.id">{{ option.text }} -
                        {{ option.is_checked ? 'yes' : 'no' }}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FileUpload from "@/common/components/FileUpload.vue";
import {toRaw} from "vue";

export default {
    name: "TakeQuestion",
    components: {FileUpload},

    emits: ['onUserResponse', 'onClear'],

    props: {
        question: {
            type: Object
        },

        options: {
            type: Array
        },

        responses: {
            type: Array
        }
    },

    data: () => ({
        selected: [],
        text: '',
        images: []
    }),

    mounted() {
        // console.log(this.responses);
    },

    computed: {
        _selected: {
            get() {

            },
            set(value) {
                console.log(value);
            }
        },
    },

    methods: {


        is_user_checked(option) {
            const is_checked = this.responses.some(r => r.question_id === option.question_id && r.option_id === option.id);
            console.log(option.text, is_checked);
            return is_checked;
        },

        convert: (obj) => ({
            quiz_id: obj.quiz_id,
            question_id: obj?.question_id,
            option_id: obj?.id,
            type: obj?.control_type || obj?.variant_type,
            data: [obj?.text] || obj?.images
        }),

        onOptionSelected(e) {

            /* const eventObject = {
                 quiz_id: this.question.quiz_id,
                 question_id: this.question.id,
                 option_id: e.target.value,
                 type: e.target.type,
                 data: [e.target.textContent]
             };

             console.log(eventObject);*/

            // this.$emit('onUserResponse', this.prepareResponse(this.selected));
            // this.$emit('onUserResponse', eventObject);
        },

        onTextChanged() {
            const eventObject = {
                quiz_id: this.question.quiz_id,
                question_id: this.question.id,
                option_id: '',
                type: 'text',
                data: [this.text]
            };

            this.$emit('onUserResponse', eventObject);
        },

        onImageUpload(image) {

            this.images = [...this.images, image.generated_name];

            const eventObj = {
                quiz_id: this.question.quiz_id,
                question_id: this.question.id,
                option_id: '',
                type: 'image',
                data: this.images.map(toRaw)
            };

            this.$emit('onUserResponse', eventObj);
        },

        onImageRemove(e) {
            this.images = this.images.filter(generated_name => generated_name !== e.target.dataset.id);
            this.$emit('onClear', {
                type: this.question?.control_type || this.question?.variant_type,
                question_id: this.question.id,
                item_id: e.target.dataset.id
            });
        },

        prepareResponse(selected) {
            if (Array.isArray(selected)) {
                return selected.map(this.convert)
            }

            return this.convert(selected);
        }
    }

}
</script>

<style scoped>

.input-group input {
    font-size: 0.9rem;
}

.input-group input[disabled] {
    background: #fff;
}

.form-check:hover {
    background: rgba(102, 165, 250, 0.5);
    cursor: pointer;
}

.form-check {
    border-bottom: 1px solid var(--bs-primary);
}

.form-check:last-child {
    /*border-bottom: none !important;*/
}

.form-check-label {
    margin-top: 3px;
}

.image-remove-icon {
    position: absolute;
    top: -15px;
    right: 0;
    font-size: 1.2rem;
}

</style>
