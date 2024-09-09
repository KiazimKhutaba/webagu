<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption">

            <div class="d-flex gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="view_type" id="viewTypeList" value="list" v-model="view_type">
                    <label class="form-check-label cursor-pointer" for="viewTypeList">
                        Список
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="view_type" id="viewTypeCard" value="card" v-model="view_type">
                    <label class="form-check-label cursor-pointer" for="viewTypeCard">
                        Карточки
                    </label>
                </div>
            </div>

        </page-header>

        <div class="row" v-if="view_type === 'card'">
            <div class="col-sm-12 col-md-6 col-lg-2 mb-3" v-for="user in users">
                <user-card-fragment :user="user" />
            </div>
        </div>

        <div class="row" v-else>

            <div class="col-md-12">
                <input type="text"
                       class="form-control form-select-sm mb-3"
                       placeholder="Введите имя пользователя..."
                       v-model="usernameText" @keyup="onUsernameSearch" />
            </div>

            <div class="col-md-12">
                <q-loading :status="loading">
                    <q-table :cols="cols" :rows="users">
                        <template #item="{item}">
                            <td>{{ item.lastname }} {{ item.firstname }}</td>
                            <td>{{ item.email }}</td>
                            <td>{{ departmentTr(item.department) }}</td>
                            <td>
                                <select class="form-select form-select-sm" name="user-status" :value="item.status" @change="(e) => onUserActivationStatusChange(e, item.id)">
                                    <option v-for="(status_name, status_val) in userActivationStatuses" :value="status_val">
                                        {{ status_name }}
                                    </option>
                                </select>
                            </td>
                            <td>{{ dt_format(item.created_at) }}</td>
                            <td>
                                <router-link :to="`/user/${item.id}/profile`">Профиль</router-link>
                            </td>
                        </template>
                    </q-table>
                </q-loading>
            </div>

        </div>

        <div class="row">
            <pagination :pagination="pagination" />
        </div>

    </dashboard-wrapper>
</template>

<script>
import QLoading from "@/common/components/QLoading.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import {usersService} from "@/modules/users/api/UsersService.js";
import QTable from "@/common/components/QTable.vue";
import TablePagination from "@/common/components/TablePagination.vue";
import Pagination from "@/common/components/Pagination.vue";
import UserCardFragment from "@/modules/users/components/UserCardFragment.vue";
import {mapMutations, mapState} from "vuex";


export default {
    name: "UsersPage",
    components: {UserCardFragment, Pagination, TablePagination, QTable, DashboardWrapper, PageHeader, QLoading},
    data() {
        return {
            users: [],
            page: null,
            loading: false,
            cols: {
                name: 'Имя',
                email: 'Почта',
                department: 'Группа',
                status: 'Статус',
                created_at: 'Зарегистрирован',
                actions: 'Действия'
            },
            userActivationStatuses: {
                'activated': 'Активирован',
                'not_activated': 'Не активирован'
            },
            pagination: null,
            usernameText: '',
            //view_type: '',
        }
    },
    async mounted() {
        await this.getUsers();
    },

    computed: {
        ...mapState('appSettingsStore', ['usersPageViewType']),

        view_type: {
            get() {
                return this.usersPageViewType;
            },
            set(value) {
                this.SET_USERS_PAGE_VIEW_TYPE(value);
            }
        },

        /*async _users() {
            return this.usernameText.trim() !== ''
                ? await this.searchUsers(this.usernameText)
                : this.users;
        }*/
    },

    methods: {

        ...mapMutations('appSettingsStore', ['SET_USERS_PAGE_VIEW_TYPE']),

        async onUsernameSearch() {
            return this.usernameText.trim() !== ''
                ? await this.searchUsers(this.usernameText)
                : await this.getUsers();
        },

        async searchUsers(username) {
            try {
                const { data } = await usersService().search(username);
                this.users = data.data;
                this.pagination = data;
            }
            catch (e) {
                // alert(e);
                console.error(e);
            }

        },

        async getUsers() {
            try {
                this.loading = true;
                const { data } = await usersService().paginate(this.$route.query);
                this.users = data.data;
                this.pagination = data;
            }
            catch (e) {
                // alert(e);
                console.error(e);
            }
            finally {
                this.loading = false;
            }
        },

        departmentTr(n) {
            return {
                finance: 'Финансы',
                accountant: 'Бухчет',
                national: 'Нацэкономика'
            }[n] || 'Неизвестный';
        },

        async onUserActivationStatusChange(e, user_id) {
            try {
                await usersService().changeActivationStatus(e.target.value, user_id);
            }
            catch (e) {
                alert(e);
            }
        }
    }
}
</script>

<style scoped>

</style>
