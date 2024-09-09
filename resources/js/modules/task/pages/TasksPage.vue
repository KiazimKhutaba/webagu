<template>
    <dashboard-wrapper :has-content="false" :is-loading="isLoading">

        <template v-if="!isFormOpen">
            <page-header :caption="$route.meta.caption">
                <button class="btn btn-sm btn-outline-primary" @click="openForm" v-show="is_admin()">Добавить задачу
                </button>
            </page-header>
            <div class="row">
                <div class="my-2 mb-3">
                    <select class="form-select form-select-sm bg-primary-subtle" aria-label="Фильтр по заданию"
                            v-model="selectedLectureId" @change="onLectureSelected">
                        <option selected value="0">Все темы</option>
                        <option v-for="lecture in lectures" :value="lecture.id">{{ lecture.title }}</option>
                    </select>
                </div>
                <template v-for="task in tasks">
                    <div class="col-lg-4 col-xl-3 mb-3" v-if="is_admin() || task.is_visible">
                        <task-card :obj="task" url="tasks"
                                   @on-remove="onTaskRemove"
                                   @on-toggle-visibility="onToggleVisibility"
                                   @on-toggle-is-seminar="onToggleIsSeminar"
                                   @on-edit=""
                        />
                    </div>
                </template>

            </div>

            <pagination :pagination="pagination" />
        </template>

        <template v-else>
            <page-header caption="Новая задача">
                <button class="btn btn-sm btn-outline-primary" @click="closeForm">Закрыть форму</button>
            </page-header>
            <task-form @on-created="onTaskCreated" :is-editing-mode="false"/>
        </template>


    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import TaskCard from "@/modules/task/components/TaskCard.vue";
import TaskForm from "@/modules/task/components/TaskForm.vue";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";
import {tasksService} from "@/modules/task/api/TasksService.js";
import {mapMutations, mapState} from "vuex";
import Pagination from "@/common/components/Pagination.vue";

export default {
    name: "TasksPage",
    components: {Pagination, TaskForm, TaskCard, PageHeader, DashboardWrapper},
    data() {
        return {
            tasks: [],
            isFormOpen: false,
            isLoading: false,
            hasContent: false,
            //selectedLectureId: 0,
            pagination: null,
            lectures: []
        }
    },
    async mounted() {
        try {
            this.isLoading = true;
            await this.getListOfTasks();
            // this.pagination = data;
            this.hasContent = true;

            this.lectures = await this.getLectures();
        } catch (e) {
            this.hasContent = false;
        } finally {
            this.isLoading = false;
        }

    },

    computed: {
        ...mapState('appSettingsStore', ['tasksPageSelectedLecture']),

        selectedLectureId: {
            get() {
                return this.tasksPageSelectedLecture;
            },
            set(value) {
                this.SET_TASKS_PAGE_SELECTED_LECTURE(value);
            }
        }
    },

    methods: {

        ...mapMutations('appSettingsStore', ['SET_TASKS_PAGE_SELECTED_LECTURE']),

        async getListOfTasks() {
            const queryParams = this.$route.query;

            let _data = null;
            if(this.is_admin()) {
                const {data} = await tasksService().getAllByLectureId({...queryParams, lecture_id: this.selectedLectureId});
                _data = data;
            }
            else {
                const {data} = await tasksService().getStudentTasks({...queryParams, lecture_id: this.selectedLectureId});
                _data = data;
            }

            this.tasks = _data.data;
            this.pagination = _data;

        },

        async onToggleVisibility(task) {
            await tasksService().toggleVisibility(task);
        },

        async onToggleIsSeminar(task) {
            await tasksService().update(task.id, {is_seminar: task.is_seminar});
        },

        async getLectures() {
            const {data} = await lecturesService().pluck(['id', 'title']);
            return data;
        },

        async onLectureSelected() {
            await this.getListOfTasks();
        },

        openForm() {
            this.isFormOpen = true;
        },

        closeForm() {
            this.isFormOpen = false;
        },

        onTaskCreated(task) {
            this.tasks = [...this.tasks, task];
            this.closeForm();
        },

        async onTaskRemove(task) {

            const {id, title} = task;

            if (confirm(`Вы действительно хотите удалить задачу "${title}" ?`)) {
                try {
                    await tasksService().remove(id);
                    this.tasks = this.tasks.filter(_task => _task.id !== +id);
                } catch (e) {
                    alert(e.statusText || e.response.data.message);
                }

            }
        }
    }
}
</script>

<style scoped>

</style>
