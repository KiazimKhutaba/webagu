<template>
    <dashboard-wrapper>
        <page-header :caption="caption">
            <button class="btn btn-sm btn-outline-primary" @click="goto('quizzes')">
                Вернуться
            </button>
        </page-header>

        <div class="mx-1">
            <q-table :cols="cols" :rows="list">
                <template #item="{item}">
                    <td>{{ item?.full_name }}</td>
                    <td>{{ item?.quiz_title }}</td>
                    <td>{{ dt_format(item?.created_at) }}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   :id="'access_request_' + item?.request_id"
                                   :checked="item?.granted"
                                   :data-id="item?.request_id"
                                   :data-name="item?.full_name"
                                   :data-user-id="item?.user_id"
                                   @change="onToggle"
                            >
                            <label class="form-check-label cursor-pointer" :for="'access_request_' + item?.request_id">
                                {{ item?.granted ? 'Да' : 'Нет' }}
                            </label>
                        </div>
                    </td>
                </template>
            </q-table>
        </div>
    </dashboard-wrapper>
</template>

<script>
import PageHeader from "@/common/components/PageHeader.vue";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import {quizService} from "@/modules/quiz/api/QuizService.js";
import QTable from "@/common/components/QTable.vue";

export default {
    name: "QuizAccessRequestsPage",
    components: {QTable, DashboardWrapper, PageHeader},

    data: () => ({
        list: [],
        cols: {
            full_name: 'Студент',
            quiz_title: 'Название теста',
            requested_at: 'Запрошен в',
            granted: 'Предоставлен',
        }
    }),

    async mounted() {
        await this.getQuizAccessRequests(this.$route.params.id);
    },

    computed: {
      caption() {
          return `${this.$route.meta.caption}`;
      }
    },

    methods: {
        async getQuizAccessRequests(quiz_id) {
            const {data} = await quizService().getQuizAccessRequests(quiz_id);
            this.list = data;
        },

        async onToggle(e)
        {
            const is_checked = e.target.checked;
            const user_id = e.target.dataset.userId;
            const request_id = e.target.dataset.id;
            const username = e.target.dataset.name;

            quizService().grantUserAccessToQuiz(user_id, request_id).then(() => {
                this.rows.find(r => +r.request_id === +request_id).granted = is_checked;
            });

            /*if(confirm(`Вы действительно хотите одобрить заявку на смену пароля для ${username}?`))
            {

            }*/

            e.preventDefault();
            e.stopPropagation();
        },
    },
}
</script>

<style scoped>

</style>
