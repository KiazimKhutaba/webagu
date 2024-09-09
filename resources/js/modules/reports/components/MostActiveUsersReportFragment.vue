<template>
    <q-table :cols="cols" :rows="rows" v-if="rows?.length > 1">
        <template #item="{item}">
            <!--<td>{{ item.user_id }}</td>-->
            <td>
                <router-link :to="userProfileLink(item.user_id)">{{ item.username }}</router-link>
            </td>
            <td>{{ item.activity }}</td>
        </template>
    </q-table>
</template>

<script>
import QTable from "@/common/components/QTable.vue";
import {logsService} from "@/modules/logs/api/LogService.js";

export default {
    name: "MostActiveUsersReportFragment",
    components: {QTable},
    data: () => ({
        cols: {
            //user_id: 'ID студента',
            username: 'ФИО',
            activity: 'Активность'
        },
        rows: []
    }),

    async mounted() {
        const { data } = await logsService().getMostActiveUsers();
        this.rows = data;
    }

}
</script>

<style scoped>

</style>
