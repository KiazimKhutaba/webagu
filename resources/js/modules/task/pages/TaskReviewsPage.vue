<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption"/>

        <div class="my-2 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <select v-model="selectedLectureId" aria-label="Фильтр по теме"
                            class="form-select form-select-sm bg-primary-subtle" @change="onFilterChanged">
                        <option selected value="0">Все темы</option>
                        <option v-for="lecture in lectures" :value="lecture.id">{{
                                lecture.title
                            }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select v-model="selectedTaskId" aria-label="Фильтр по заданию"
                            class="form-select form-select-sm bg-primary-subtle" @change="onFilterChanged">
                        <option selected value="0">Все задания</option>
                        <option v-for="task in tasks" :value="task.id">{{ task.title }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mx-1">
            <q-table :cols="cols" :rows="reviews">
                <template #item="{item}">
                    <td>{{ item.title }}</td>
                    <td>{{ item.lecture_title }}</td>
                    <td>{{ item.student_name }}</td>
                    <!--<td>{{ item.teacher_name }}</td>-->
                    <td>
                        <span :class="`badge bg-${review(item.review_status).clazz} d-inline-block`">
                            {{ review(item.review_status).caption }}
                        </span>
                    </td>
                    <!--<td>
                        <select class="form-select form-select-sm" name="user-status" :value="item.status" @change="(e) => onUserActivationStatusChange(e, item.id)">
                        <option v-for="(status_name, status_val) in userActivationStatuses" :value="status_val">
                            {{ status_name }}
                        </option>
                        </select>
                    </td>-->
                    <td>{{ dt_format2(item.created_at) }}</td>
                    <!--<td>{{ dt_format2(item.updated_at ) }}</td>-->
                    <td>
                        <router-link :to="generateLink(item)">Открыть</router-link>
                    </td>
                </template>
            </q-table>

            <pagination :pagination="pagination"/>
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import QTable from "@/common/components/QTable.vue";
import Pagination from "@/common/components/Pagination.vue";
import {getReviewByStatusName} from "@/utils/reviewTypes.js";
import {tasksService} from "@/modules/task/api/TasksService.js";
import {reviewService} from "@/modules/task/api/ReviewService.js";
import removeDuplicates from "@/utils/removeDuplicates.js";
import {mapMutations, mapState} from "vuex";

export default {
    name: "TaskReportsPage",
    components: {Pagination, QTable, PageHeader, DashboardWrapper},
    data: () => {
        return {
            cols: {
                //id: "Id",
                // task_id: "",
                title: "Задание",
                lecture: "Лекция",
                student_name: "Студент",
                //teacher_name: "Преподаватель",
                review_status: "Статус",
                created_at: "Сдана",
                //updated_at: "Повторно сдана",
                actions: "Действия"
            },
            reviews: [],
            tasks: [],
            //selectedTaskId: 0,
            //selectedLectureId: 0,
            pagination: null
        }
    },

    computed: {

        ...mapState('appSettingsStore', ['tasksReviews']),

        selectedTaskId: {
            get() {
                return this.tasksReviews.selectedTask;
            },
            set(value) {
                this.SET_TASKS_REVIEWS_FILTERS({ selectedTask: value });
            }
        },

        selectedLectureId: {
            get() {
                return this.tasksReviews.selectedLecture;
            },
            set(value) {
                this.SET_TASKS_REVIEWS_FILTERS({ selectedLecture: value });
            }
        },

        lectures() {
            const list = [];
            for (const {lecture} of this.tasks) {
                list.push(lecture);
            }

            return removeDuplicates(list, 'id');
        }
    },

    async mounted() {

        const paginated = await this.getReviews(this.selectedTaskId, this.selectedLectureId);
        this.reviews = paginated.data;
        this.pagination = paginated;

        const res2 = await tasksService().getAll();
        this.tasks = res2.data.data || res2.data;
    },

    methods: {

        ...mapMutations('appSettingsStore', ['SET_TASKS_REVIEWS_FILTERS']),

        generateLink(item) {
            const route =  this.$router.resolve({
                name: 'tasks.get',
                params: {id: item.task_id},
                query: {student_id: item.student_id, from: 'tasks.reports', ...this.$route.query}
            });

            return route.fullPath;
            // return `/tasks/${item.task_id}?student_id=${item.student_id}&from=tasks.reports`
        },

        review(status) {
            return getReviewByStatusName(status);
        },

        async getReviews(task_id = 0, lecture_id = 0) {
            const queryParams = this.$route.query;
            const {data} = await reviewService().getAllByTaskId({...queryParams, lecture_id, task_id});
            return data;
        },

        async onFilterChanged() {
            const paginated = await this.getReviews(this.selectedTaskId, this.selectedLectureId);
            this.reviews = paginated.data;
            this.pagination = paginated;
        },
    },

}
</script>

<style scoped>

</style>
