<template>
    <q-table :cols="cols" :rows="views">
        <template #item="{item}">
            <td>{{ item.username }}</td>
            <td>{{ item.count }}</td>
            <td>{{ dt_format(item.first_viewed_at) }}</td>
            <td>{{ dt_format(item.last_viewed_at) }}</td>
        </template>
    </q-table>
</template>

<script>
import QTable from "@/common/components/QTable.vue";
export default {
    name: "PostViewHistoryFragment",
    components: {QTable},
    props: {
        views: {
            type: Array,
            default: []
        }
    },

    data: () => ({
        cols: {
            username: 'ФИО',
            count: 'Просмотры',
            first_viewed_at: 'Первый',
            last_viewed_at: 'Последний'
        }
    }),
}
</script>

<style scoped>

</style>
