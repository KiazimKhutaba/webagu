<template>

    <div class="mx-1">

        <div class="row my-3 gx-2 gy-2">
            <div class="col-md-auto">
                <button class="btn btn-sm btn-outline-success" @click="exportReport">Экспорт в Excel</button>
            </div>
            <div class="col-md flex-grow-1">
                <button :class="{'btn btn-sm': true, 'btn-outline-primary': !commonReportIsCheckingMode, 'btn-primary': commonReportIsCheckingMode}"
                        @click="toggleCheckingMode">Режим проверки
                </button>
            </div>
        </div>

        <div class="row mb-4">

            <div class="col-md-3">
                <select class="form-select form-select-sm bg-primary-subtle" v-model="selected_task_type"
                        @change="onTaskTypeSelected" title="Типы заданий">
                    <option value="-1" selected>Все типы</option>
                    <option value="0">Письменные (подготовка к семинарам)</option>
                    <option value="1">Семинары</option>
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-select form-select-sm bg-primary-subtle" v-model="selected_department"
                        @change="onDepartmentSelected">
                    <option value="all" selected>Все направления</option>
                    <option value="finance">Финансы и кредит</option>
                    <option value="accountant">Бухгалтерский учет и аудит</option>
                    <option value="national">Национальная экономика</option>
                </select>
            </div>

            <div class="col-md-3" v-if="lectures.length > 0">
                <select class="form-select form-select-sm bg-primary-subtle" v-model="selected_lecture_id"
                        @change="onLectureSelected">
                    <option value="0" selected>Все темы</option>
                    <option :value="lecture.id" :key="lecture.id" v-for="lecture in lectures">#{{ lecture.id }}
                        {{ lecture.title }}
                    </option>
                </select>
            </div>

            <div class="col-md-3" v-if="_tasks.length > 0">
                <select class="form-select form-select-sm bg-primary-subtle" v-model="selected_task_id"
                        @change="onTaskSelected">
                    <option value="0" selected>Все задания</option>
                    <option :value="task.id" v-for="task in _tasks">#{{ task.id }} {{ task.title }}</option>
                </select>
            </div>

            <div class="col-md-3" v-else>
                <select class="form-select form-select-sm bg-primary-subtle">
                    <option value="0" selected>Все задания</option>
                </select>
            </div>

        </div>


        <q-table :cols="cols" :rows="rows" v-if="rows?.length > 1" id="commonReportData">
            <template #item="{item}">
                <td>
                    <router-link :to="userProfileLink(item.student_id)">{{ item.name }}</router-link>
                </td>
                <td v-for="val in without_properties(item)" class="report-link">

                    <template v-if="commonReportIsCheckingMode">
                        <select
                            :class="`form-select form-select-sm text-white bg-${review(val.task_status).clazz} cursor-pointer`"
                            v-model="val.task_status" @change="onTaskStatusChanged"
                            :data-student-id="item.student_id" :data-task-id="val.task_id" :data-lecture-id="val.lid"
                        >
                            <option :value="review_type.name" v-for="review_type in reviewTypes">
                                {{ review_type.caption }}
                            </option>
                        </select>
                    </template>

                    <template v-else>
                        <router-link :to="generateLink(val.task_id, item.student_id)"
                                     :class="`badge bg-${review(val.task_status).clazz}`"
                                     v-if="isShowLink(val.task_status)">
                            {{ review(val.task_status).caption }}
                        </router-link>
                        <span :class="`badge bg-${review(val.task_status).clazz} d-inline-block`" v-else>
                            {{ review(val.task_status).caption }}
                         </span>
                    </template>

                </td>
            </template>
        </q-table>

        <h2 class="h2" v-else>Нет данных</h2>
    </div>
</template>

<script>
import QTable from "@/common/components/QTable.vue";
import {defaultType, getReviewByStatusName, reviewTypes} from "@/utils/reviewTypes.js";
import {lecturesService} from "@/modules/lecture/api/LecturesService.js";
import {reportsService} from "@/modules/reports/api/ReportsService.js";
import {reviewService} from "@/modules/task/api/ReviewService.js";
import {tasksService} from "@/modules/task/api/TasksService.js";
import {mapMutations, mapState} from "vuex";
import * as XLSX from "xlsx";

export default {
    name: "CommonReportFragment",
    components: {QTable},

    data: () => ({
        cols: {},
        rows: [],
        lectures: [],
        tasks: [],
        reviewTypes: reviewTypes(),
    }),

    async mounted() {
        const res1 = await lecturesService().pluck(['id', 'title']);
        this.lectures = res1.data;

        const res = await tasksService().pluck(['id', 'title', 'lecture_id']);
        this.tasks = res.data;

        await this.onLectureSelected();
        // await this.onTaskSelected();
    },

    computed: {

        ...mapState('appSettingsStore', [
            'commonReportSelectedLectureId',
            'commonReportSelectedTaskId',
            'commonReportSelectedDepartment',
            'commonReportSelectedTaskType',
            'commonReportIsCheckingMode',
        ]),

        selected_lecture_id: {
            get() {
                return this.commonReportSelectedLectureId;
            },
            set(value) {
                this.SET_COMMON_REPORT_SELECTED_LECTURE_ID(value);
            }
        },

        selected_task_id: {
            get() {
                return this.commonReportSelectedTaskId;
            },
            set(value) {
                this.SET_COMMON_REPORT_SELECTED_TASK_ID(value);
            }
        },

        selected_department: {
            get() {
                return this.commonReportSelectedDepartment;
            },
            set(value) {
                this.SET_COMMON_REPORT_SELECTED_DEPARTMENT(value);
            }
        },

        selected_task_type: {
            get() {
                return this.commonReportSelectedTaskType;
            },
            set(value) {
                this.SET_COMMON_REPORT_SELECTED_TASK_TYPE(value);
            }
        },

        _tasks() {
            return this.selected_lecture_id > 0 ? this.tasks.filter(t => t.lecture_id === this.selected_lecture_id) : this.tasks;
        }
    },

    methods: {

        ...mapMutations('appSettingsStore', [
            'SET_COMMON_REPORT_SELECTED_LECTURE_ID',
            'SET_COMMON_REPORT_SELECTED_TASK_ID',
            'SET_COMMON_REPORT_SELECTED_DEPARTMENT',
            'SET_COMMON_REPORT_SELECTED_TASK_TYPE',
            'TOGGLE_COMMON_REPORT_CHECKING_MODE'
        ]),

        async onTaskStatusChanged(e)
        {
            const review = {
                lecture_id: e.target.dataset.lectureId,
                student_id: e.target.dataset.studentId,
                task_id: e.target.dataset.taskId,
                review_status: e.target.value
            };

            await reviewService().create(review);
        },


        toggleCheckingMode() {
            this.TOGGLE_COMMON_REPORT_CHECKING_MODE(!this.commonReportIsCheckingMode);
        },

        exportReport() {

            const table = document.querySelector("#commonReportData > table");

            /* Generate workbook object from HTML table with styles */
            const worksheet = XLSX.utils.table_to_sheet(table, {raw: true});

            /* Create Excel file and trigger download */
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            XLSX.writeFile(workbook, 'CommonReport.xlsx');

        },

        async onDepartmentSelected() {
            const {data, cols} = await this.getReport(
                this.selected_task_id,
                this.selected_lecture_id,
                this.selected_department,
                this.selected_task_type
            );
            this.cols = cols;
            this.rows = data;
        },

        async onLectureSelected() {
            const {data, cols} = await this.getReport(
                this.selected_task_id,
                this.selected_lecture_id,
                this.selected_department,
                this.selected_task_type
            );
            this.cols = cols;
            this.rows = data;
        },

        async onTaskSelected() {
            const {data, cols} = await this.getReport(
                this.selected_task_id,
                this.selected_lecture_id,
                this.selected_department,
                this.selected_task_type
            );

            this.cols = cols;
            this.rows = data;
        },

        async onTaskTypeSelected() {
            const {data, cols} = await this.getReport(
                this.selected_task_id,
                this.selected_lecture_id,
                this.selected_department,
                this.selected_task_type
            );

            this.cols = cols;
            this.rows = data;
        },

        async getReport(task_id, lecture_id, department, task_type) {
            const {data} = await reportsService().getTaskStatuses({
                task_id, lecture_id, department, task_type
            });

            const _cols = Object.keys(data[0]).filter(col => !['student_id', 'name'].includes(col)).reduce((a, v) => ({
                ...a,
                ['task_' + v]: '#' + v
            }), {});

            const cols = Object.assign({name: 'Имя'}, _cols);
            return {data, cols};
        },

        isShowLink(task_status) {
            return task_status !== 'waiting_execution'
        },

        generateLink(task_id, student_id) {
            return this.$router.resolve({
                name: 'tasks.get',
                params: {id: task_id},
                query: {student_id, from: 'reports'}
            });
        },

        without_properties(item) {
            const _item = {...item};
            delete _item['name'];
            // delete _item['task_id'];
            delete _item['student_id'];
            return _item;
        },

        review(review_status) {
            return getReviewByStatusName(review_status) || defaultType;
        }
    }
}
</script>

<style scoped>
.report-link a {
    text-decoration: none;
    font-size: 0.65rem !important;
}

.report-link a:hover {
    text-decoration: underline;
}

.checking__select {

}

</style>
