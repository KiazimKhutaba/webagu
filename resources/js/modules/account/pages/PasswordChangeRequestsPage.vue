<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption"/>

        <div class="mx-1" v-if="!rows.length">
            <h5 class="h5">Заявок пока нет :)</h5>
        </div>

        <q-table :cols="cols" :rows="rows" v-else>
            <template #item="{item}">
                <td>
                    <router-link :to="href('user.profile', { id: item?.user_id })">{{ item.name }}</router-link>
                </td>
                <td>{{ item.phone }}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               :id="'password_change_request_' + item.id"
                               :checked="item.is_approved"
                               :data-id="item.id"
                               :data-name="item.name"
                               @change="onToggle"
                        >
                        <label class="form-check-label cursor-pointer" :for="'password_change_request_' + item.id">
                            {{ item.is_approved ? 'Одобрена' : 'Ожидает одобрения' }}
                        </label>
                    </div>
                </td>
                <td>{{ dt_format(item.created_at) }}</td>
                <td>
                    <span class="text-primary" :data-id="item.id" @click="onRemove">Удалить</span>
                </td>
            </template>
        </q-table>

        <div class="row overflow-x-auto">
            <pagination :pagination="pagination"/>
        </div>
    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import {requestsService} from "@/modules/account/api/RequestsService.js";
import QTable from "@/common/components/QTable.vue";
import Pagination from "@/common/components/Pagination.vue";

export default {
    name: "RequestsPage",
    components: {Pagination, QTable, PageHeader, DashboardWrapper},

    data: () => ({
        rows: [],
        cols: {
            name: "ФИО",
            phone: "Логин",
            is_approved: "Статус",
            created_at: "Заявка отправлена",
            actions: 'Действия'
        },
        pagination: null
    }),

    async mounted() {
        const {data} = await requestsService().paginate(this.$route.query);
        this.rows = data.data;
        this.pagination = data;
    },


    methods: {
        async onToggle(e)
        {
            const is_checked = e.target.checked;
            const id = e.target.dataset.id;
            const username = e.target.dataset.name;

            if(confirm(`Вы действительно хотите одобрить заявку на смену пароля для ${username}?`))
            {
                requestsService().update(id, { is_approved: is_checked }).then(() => {
                    this.rows.find(r => +r.id === +id).is_approved = is_checked;
                });

            }
        },

        async onRemove(e)
        {
            const id = e.target.dataset.id;

            if(confirm(`Вы действительно хотите удалить заявку с идентификатором #${id}?`))
            {
                requestsService().remove(id).then(() => {
                    this.rows = this.rows.filter(r => +r.id !== +id);
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
