<template>
    <dashboard-wrapper>
        <page-header :caption="getPageTitle">
            <button class="btn btn-sm btn-primary" @click="$router.go(-1)">Назад</button>
        </page-header>


        <div class="mt-3">
            <nav-tabs id="user-profile-page-nav-tab">
                <nav-tab-link name="profile" label="Профиль" class="active" />
                <nav-tab-link name="progress" label="Прогресс" />
                <nav-tab-link name="user_activity" label="Активность" v-if="is_admin()"/>
            </nav-tabs>
            <nav-tab-content id="nav-tabContent">
                <nav-tab-pane name="profile" class="active show">
                    <user-profile-fragment :user="user" />
                </nav-tab-pane>
                <nav-tab-pane name="progress">
                    <user-tasks-progress :id="user?.id" />
                </nav-tab-pane>
                <nav-tab-pane name="user_activity" v-if="is_admin()">
                    <user-activity-fragment />
                </nav-tab-pane>
            </nav-tab-content>
        </div>

    </dashboard-wrapper>
</template>

<script>
import DashboardWrapper from "@/common/components/DashboardWrapper.vue";
import PageHeader from "@/common/components/PageHeader.vue";
import NavTabLink from "@/common/components/navTabs/NavTabLink.vue";
import NavTabPane from "@/common/components/navTabs/NavTabPane.vue";
import NavTabs from "@/common/components/navTabs/NavTabs.vue";
import NavTabContent from "@/common/components/navTabs/NavTabContent.vue";

import {usersService} from "@/modules/users/api/UsersService.js";
import UserProfileFragment from "@/modules/users/components/UserProfileFragment.vue";
import UserTasksProgress from "@/modules/users/components/UserTasksProgress.vue";
import UserActivityFragment from "@/modules/users/components/UserActivityFragment.vue";


export default {
    name: "UserProfilePage",
    components: {
        UserActivityFragment,
        UserTasksProgress,
        UserProfileFragment, PageHeader, DashboardWrapper, NavTabContent, NavTabs, NavTabPane, NavTabLink},

    data() {
        return {
            profilePhoto: null,
            user: null
        }
    },

    created() {
        // const tabEl = document.querySelector('#user-profile-page-nav-tab')
        /*document.addEventListener('shown.bs.tab', event => {
            //event.target // newly activated tab
            //event.relatedTarget // previous active tab
            console.log(event.target.href);
        })*/
    },

    async mounted() {
        const user_id = +this.$route.params.id;
        await this.getUserProfileData(user_id);
    },

    computed: {
        getPageTitle() {
            return `${this.$route.meta.caption} ${this.user?.lastname} ${this.user?.firstname}`;
        }
    },

    methods: {

        async getUserProfileData(user_id) {
            const res = await usersService().getById(user_id);
            this.user = res.data;
        },
    }
}
</script>

<style scoped>

</style>
