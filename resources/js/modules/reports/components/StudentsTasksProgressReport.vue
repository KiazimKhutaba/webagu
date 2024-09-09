<template>

    <div class="my-3">
        <button class="btn btn-sm btn-outline-success" @click="exportReport">Экспорт в Excel</button>
    </div>

    <q-table-extended :cols="cols" v-if="rows?.length > 1">
        <tbody>
        <tr v-for="(item, counter) in rows" :class="{'danger-zone': is_danger_zone(item)}">
            <th scope="row">{{ ++counter }}</th>
            <td>
                <router-link :to="userProfileLink(item.student_id)">{{ item.name }}</router-link>
            </td>
            <td>{{ item.accepted }}</td>
            <td>{{ item.waiting_execution }}</td>
            <td>{{ item.rejected }}</td>
            <td>{{ item.returned }}</td>
            <!-- <td>{{ item.progress }}%</td> -->
        </tr>
        </tbody>
    </q-table-extended>
</template>

<script>
import DashboardWrapper from "@/common/components//DashboardWrapper.vue";
import {reportsService} from "@/modules/reports/api/ReportsService.js";
import QTable from "@/common/components//QTable.vue";
import PageHeader from "@/common/components//PageHeader.vue";
import QTableExtended from "@/common/components//QTableExtended.vue";
import export2Excel from "@/utils/export2Csv.js";

export default {
    name: "StudentsTasksProgressReport",
    components: {QTableExtended, PageHeader, QTable, DashboardWrapper},

    data: () => ({
        cols: {
            //user_id: 'ID студента',
            name: 'ФИО',
            accepted: 'Принято',
            waiting_execution: 'Ожидает выполнения',
            rejected: 'Не принято',
            returned: 'Возвращено на доработку',
            // progress: 'Прогресс',
            // progress2: 'Прогресс2',
        },
        rows: [],
    }),

    async mounted() {
        const {data} = await reportsService().getTasksProgress();
        this.rows = data;
    },


    methods: {

        is_danger_zone({ uncompleted }) {
            return uncompleted > 3;
        },

        exportReport() {
            export2Excel(this.cols, this.rows, 'TasksReports.csv');
        }
    }
}
</script>

<style scoped>
thead {
    background: #0b5ed7;
    color: white;
}

tbody tr:hover {
    background: #f4f4f4 !important;
}

.danger-zone {
    background: var(--bs-warning);
}

tbody tr th, td {
    background: inherit;
}

</style>
