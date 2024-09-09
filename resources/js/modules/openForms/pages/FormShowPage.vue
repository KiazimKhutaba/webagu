<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <router-link class="btn btn-sm btn-outline-primary" :to="url('openforms.formslist')">
                Назад
            </router-link>
        </page-header>

        <div class="mx-1 mb-3">

            <div class="accordion mb-3 d-none" id="debugAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" style="font-size: 0.8rem" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Отладка
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#debugAccordion">
                        <div class="accordion-body">
                            <div class="max-h-300px overflow-y-auto">
                                <!-- <pre>{{ JSON.stringify($store.state['openFormStore'], null, 2) }}</pre> -->
                                <pre>{{ JSON.stringify(responses, null, 2) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">

                <div class="card-header fw-bold">{{ quiz.title }}</div>

                <div class="card-body">
                    <!--                    <div class="card-title">{{ quiz.title }}</div>-->
                    <div class="card-text text-secondary">{{ quiz.description }}</div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col"><span class="text-secondary">Продолжительность:</span> {{ quiz.duration }}
                            минут
                        </div>
                        <!--                        <div class="col"><span class="text-secondary">Доступен: </span>
                                                    {{ quiz.is_available ? 'Да' : 'Нет' }}
                                                </div>-->
                    </div>
                </div>
            </div>

            <take-question
                v-for="q in questions"
                :key="q.id"
                :question="q"
                :options="_options(q.id)"
                :responses="_responses(q.id)"
                @on-user-response="onData"
                @on-clear="onClear"
            />

            <button class="btn btn-sm btn-warning w-100" @click="sendOnReview">Отправить на проверку</button>

        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {openFormsService} from "@/modules/openForms/api/BaseFormService.js";
import FileUpload from "@/common/components/FileUpload.vue";
import TakeQuestion from "../components/TakeQuestion.vue";
import {mapMutations, mapState} from "vuex";
import {toRaw} from "vue";
import {v4} from "uuid";

export default {
    name: "FormShowPage",
    components: {TakeQuestion, FileUpload, PageHeader, DashboardWrapper},

    data: () => ({
        quiz: {},
        questions: [],
        options: [],
    }),

    created() {
        // const that = this;
        /*window.onbeforeunload = function () {
            that.CLEAR_STATE();
            // localStorage.setItem('page_reload', 'true');
        }*/

    },

    async mounted() {

        const {data} = await openFormsService().getById(this.$route.params.id);
        this.quiz = data.quiz;
        this.questions = data.questions;
        this.options = data.options;

        if (!this.quiz_started_at) {
            this.SET_QUIZ_STARTED_AT(Date.now());
        }

        /*this.queryPermission('geolocation', async () => {

        }, () => {
            alert('Для того, чтобы тест открылся нужно активировать геолокацию!');
        });*/

        /* navigator.permissions.query({name:'geolocation'}).then(function(result) {
             // Will return ['granted', 'prompt', 'denied']
             console.log(result.state);
         });

 */
        window.$responses = this.responses;

    },

    computed: {
        ...mapState('ofQuizTakeStore', {
            responses: 'responses',
            quiz_started_at: 'quiz_started_at'
        }),
    },

    methods: {

        ...mapMutations('ofQuizTakeStore', ['ADD_RESPONSE', 'RESPONSE_REMOVE_IMAGE', 'CLEAR_STATE', 'SET_QUIZ_STARTED_AT']),

        /*queryPermission(permission_name, onGrant, onReject) {
            if (navigator.permissions) {
                navigator.permissions
                    .query({ name: permission_name})
                    .then(permission => permission.state === "granted" ? onGrant() : onReject())
            }
        }*/

        async sendOnReview() {

            if (0 === this.responses.length) {
                alert('Вы не заполнили тест!');
                return;
            }

            const quiz_take_id = v4();
            const selected = this.responses.map(r => {
                const rawObj = toRaw(r);
                rawObj['quiztake_id'] = quiz_take_id;
                return rawObj;
            });

            const quiz_take = {
                id: quiz_take_id,
                quiz_id: this.quiz.id,
                started_at: this.quiz_started_at,
                page_switches_count: 0,
                geolocation: JSON.stringify({})
                //geolocation: JSON.stringify({latitude: coords.latitude, longitude: coords.longitude})
            };


            try {
                await openFormsService().sendForReview({quiz_take, selected});
                alert('Ваш ответ отправлен проверку, Спасибо!')
                this.redirect_to('openforms.formslist');
            }
            catch (e) {
                alert(e);
                console.error(e);
            }

            /* navigator.geolocation.getCurrentPosition(async ({coords}) => {

             }, (err) => {
                 alert(err.message);
             }, {
                 enableHighAccuracy: true
             })*/
        },

        _options(question_id) {
            return this.options.filter(o => o.question_id === question_id);
        },

        _responses(question_id) {
            return this.responses.filter(r => r.question_id === question_id);
        },

        onData(obj) {
            this.ADD_RESPONSE(obj)
        },

        onClear(e) {
            this.RESPONSE_REMOVE_IMAGE(e);
        }

    }
}
</script>

<style scoped>

</style>
