<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">
            <button
                :class="{'btn btn-sm btn-outline-primary': !_filterBlockVisible, 'btn btn-sm btn-primary': _filterBlockVisible }"
                @click="toggleFiltering">
                <i class="bi bi-funnel-fill" v-if="_filterBlockVisible"></i>
                <i class="bi bi-funnel" v-else></i>
                Фильтр
            </button>
        </page-header>

        <div class="mx-1 my-3" v-show="_filterBlockVisible">
            <div class="row gy-2">
                <div class="col-md-3">
                    <label class="form-label">Пользователи</label>
                    <multiselect placeholder="Выберите пользователей..." :options="usernames" v-model="_selectedUsers"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Действие</label>
                    <multiselect placeholder="Выберите действия..." :options="actions" v-model="_selectedActions"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Начало</label>
                    <input type="datetime-local" class="form-control form-control-sm" v-model="_startDatetime"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Конец</label>
                    <input type="datetime-local" class="form-control form-control-sm" v-model="_endDatetime"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">IP-адреса</label>
                    <multiselect placeholder="Выберите IP-адрес(a)..." :options="unique_ips" v-model="_filterIPs"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">URL-адреса</label>
                    <multiselect placeholder="Выберите URL-адрес(a)..." :options="unique_urls" v-model="_filterUrls"/>
                </div>
            </div>
            <div class="row mt-3 gx-2 gy-2">
                <div class="col-md-auto">
                    <input type="button" value="Получить" class="btn btn-sm btn-success" @click="onFilter"/>
                </div>
                <div class="col-md flex-grow-1">
                    <input type="button" value="Очистить фильтр" class="btn btn-sm btn-warning" @click="onFilterClear"/>
                </div>
            </div>
        </div>

        <q-table :cols="cols" :rows="rows">
            <template #item="{item}">
                <td>
                    <router-link :to="profile(item?.user_id)">{{ item.name }}</router-link>
                </td>
                <td>{{ tr(item.action_name) }}</td>
                <td>{{ item.os }}</td>
                <td>{{ item.browser }}</td>
                <td>{{ item.client_ip }}</td>
                <!--            <td>{{ item.data }}</td>-->
                <td>{{ item.url }}</td>
                <td>
                    <router-link :to="item.referer">{{ item.referer }}</router-link>
                </td>
                <td>{{ dt_format(item.created_at) }}</td>
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
import QTable from "@/common/components/QTable.vue";
import {logsService} from "@/modules/logs/api/LogService.js";
import {usersService} from "@/modules/users/api/UsersService.js";
import FileUpload from "@/common/components/FileUpload.vue";
import ChooseFilesDialog from "@/common/components/ChooseFilesDialog.vue";
import Pagination from "@/common/components/Pagination.vue";
import get_translation, {activity_log_action_types} from "@/utils/activity_log_action_type_translations.js";
import Multiselect from "@/common/components/Multiselect.vue";
import {mapMutations, mapState} from "vuex";


export default {
    name: "UsersActivityLogsPage",
    components: {Multiselect, Pagination, ChooseFilesDialog, FileUpload, QTable, PageHeader, DashboardWrapper},
    data: () => ({
        rows: [],
        cols: {
            name: "ФИО",
            action_name: "Действие",
            os: "ОС",
            browser: "Браузер",
            client_ip: "Ip",
            // data: "Мета",
            url: "URL",
            referer: "Откуда",
            created_at: "Дата",
        },
        pagination: null,
        value: '',
        usernames: [],
        unique_ips: [],
        unique_urls: [],
        // selectedUsers: [],
        // selectedActions: [],
    }),

    async mounted() {

        const {data} = await logsService().filter(this.$route.query, this.getFilterData());
        this.rows = data.data;
        this.pagination = data;

        if (this._filterBlockVisible && !this.usernames.length && !this.unique_ips.length && !this.unique_urls.length) {
            await this.fetchFilterOptions();
        }
    },

    computed: {

        ...mapState('usersActivityLogsStore', [
            'filterBlockVisible', 'selectedUsers',
            'selectedActions', 'startDatetime', 'endDatetime',
            'filterIPs', 'filterUrls'
        ]),

        _filterUrls: {
            get() {
                return this.filterUrls;
            },
            set(value) {
                this.SET_FILTER_URLS(value);
            }
        },

        _filterIPs: {
            get() {
                return this.filterIPs;
            },
            set(value) {
                this.SET_FILTER_IPS(value);
            }
        },

        _selectedUsers: {
            get() {
                return this.selectedUsers;
            },
            set(value) {
                this.SET_SELECTED_USERS(value);
            }
        },

        _selectedActions: {
            get() {
                return this.selectedActions;
            },
            set(value) {
                this.SET_SELECTED_ACTIONS(value);
            }
        },

        _startDatetime: {
            get() {
                return this.startDatetime;
            },
            set(value) {
                this.SET_FILTER_START_DATETIME(value);
            }
        },

        _endDatetime: {
            get() {
                return this.endDatetime;
            },
            set(value) {
                this.SET_FILTER_END_DATETIME(value);
            }
        },

        _filterBlockVisible: {
            get() {
                return this.filterBlockVisible;
            },
            set(value) {
                this.SET_FILTER_BLOCK_VISIBILITY(value);
            }
        },

        actions() {
            const list = [];
            for (const action in activity_log_action_types) {
                list.push({id: action, value: activity_log_action_types[action]});
            }

            return list;
        },
    },


    methods: {

        ...mapMutations('usersActivityLogsStore', [
            'SET_FILTER_BLOCK_VISIBILITY',
            'SET_SELECTED_USERS', 'SET_SELECTED_ACTIONS',
            'SET_FILTER_START_DATETIME', 'SET_FILTER_END_DATETIME',
            'SET_FILTER_IPS', 'SET_FILTER_URLS',
            'FILTER_CLEAR',
        ]),

        async fetchFilterOptions() {
            const res = await usersService().getStudents();
            this.usernames = res.data.map(user => ({id: user.id, value: `${user.lastname} ${user.firstname}`}));
            //list.sort((user1, user2) => user1.value < user2.value);

            const res_ips = await logsService().getUniqueIPs();
            this.unique_ips = res_ips.data.map((ip, index) => ({id: index, value: ip.client_ip}));

            const res_urls = await logsService().getUniqueUrls();
            this.unique_urls = res_urls.data.map((u, index) => ({id: index, value: u.url}));
        },

        async toggleFiltering()
        {
            this._filterBlockVisible = !this._filterBlockVisible;

            if (this._filterBlockVisible && !this.usernames.length && !this.unique_ips.length && !this.unique_urls.length) {
                await this.fetchFilterOptions();
            }
        },

        tr(key) {
            return get_translation(key, 'unknown');
        },

        profile(id) {
            return this.$router.resolve({
                name: 'user.profile',
                params: {id}
            })
        },

        onUsersSelected(users) {
            //this.SET_SELECTED_USERS(users);
        },

        onActionsSelected(actions) {
            // this.SET_SELECTED_ACTIONS(actions);
        },

        getFilterData() {
            return {
                // usersFull: this.selectedUsers,
                users: this.selectedUsers.map(u => u.id),
                actions: this.selectedActions.map(a => a.id),
                // start_dt: Date.parse(this.$refs.startDateTime.value) || 0,
                ips: this.filterIPs.map(i => i.value),
                urls: this.filterUrls.map(i => i.value),
                start_dt: this.startDatetime && Date.parse(this.startDatetime),
                end_dt: this.endDatetime && Date.parse(this.endDatetime),
                // end_dt: Date.parse(this.endDatetime) || 0,
                // end_dt: Date.parse(this.$refs.endDateTime.value) || 0,
            };
        },

        async onFilter() {
            const rdata = this.getFilterData();

            console.log(rdata);

            const {data} = await logsService().filter(this.$route.query, rdata);
            this.rows = data.data;
            this.pagination = data;
        },

        onFilterClear() {
            this.FILTER_CLEAR();
        },

    },
}
</script>

<style scoped>

</style>
