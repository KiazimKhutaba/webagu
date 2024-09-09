<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <select class="form-select form-select-sm bg-secondary-subtle" v-model="selected_report">
                <option value="" selected>Выберите тип отчета</option>
                <option value="common">Отчет по успеваемости</option>
                <option value="most_active">Самые активные</option>
                <option value="tasks_progress">Общий прогресс</option>
            </select>
        </page-header>

        <common-report-fragment v-if="selected_report === 'common'" />
        <most-active-users-report-fragment v-if="selected_report === 'most_active'" />
        <students-tasks-progress-report v-if="selected_report === 'tasks_progress'" />

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import QTable from "@/common/components/QTable.vue";
import CommonReportFragment from "@/modules/reports/components/CommonReportFragment.vue";
import MostActiveUsersReportFragment from "@/modules/reports/components/MostActiveUsersReportFragment.vue";
import StudentsTasksProgressReport from "@/modules/reports/components/StudentsTasksProgressReport.vue";
import {mapMutations, mapState} from "vuex";


export default {
    name: "ReportsPage",
    components: {
        StudentsTasksProgressReport,
        MostActiveUsersReportFragment, CommonReportFragment, QTable, PageHeader, DashboardWrapper},

    computed: {

        ...mapState('appSettingsStore', ['reportsPageSelectedReport']),

        selected_report: {
            get() {
                return this.reportsPageSelectedReport;
            },
            set(value) {
                this.SET_REPORTS_PAGE_SELECTED_REPORT(value);
            }
        },
    },

    methods: {
        ...mapMutations('appSettingsStore', ['SET_REPORTS_PAGE_SELECTED_REPORT']),
    }

}
</script>

<style scoped>

</style>
