<template>
    <q-table :cols="cols" :rows="rows">
        <template #item="{item}">
            <td>{{ tr(item.action_name) }}</td>
            <td>{{ item.os }}</td>
            <td>{{ item.browser }}</td>
            <td>{{ item.client_ip }}</td>
<!--            <td>{{ item.data }}</td>-->
            <td>{{ item.url }}</td>
            <td>{{ item.referer }}</td>
            <td>{{ dt_format(item.created_at) }}</td>
        </template>
    </q-table>

    <pagination :pagination="pagination" />
</template>

<script>
import {userActivityService} from "@/modules/users/api/UserActivityService.js";
import QTable from "@/common/components/QTable.vue";
import Pagination from "@/common/components/Pagination.vue";
import get_translation from "@/utils/activity_log_action_type_translations.js";

export default {
    name: "UserActivityFragment",
    components: {Pagination, QTable},
    data: () => ({
        rows: [],
        cols: {
            //username: "ФИО",
            action_name: "Действие",
            os: "ОС",
            browser: "Браузер",
            client_ip: "Ip",
            // data: "Мета",
            url: "URL",
            referer: "Откуда",
            created_at: "Дата",
        },
        pagination: null
    }),

    async mounted() {
        const data = await this.getLogs();
        this.rows = data.data;
        this.pagination = data;
        // console.log(this.rows[0]);
    },

    methods: {
        async getLogs() {
            const { data } = await userActivityService().getById(this.$route.params.id);
            return data;
        },

        tr(key) {
            return get_translation(key, 'unknown');
        }
    }
}
</script>

<style scoped>

</style>
