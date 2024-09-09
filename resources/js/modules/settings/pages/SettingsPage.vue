<template>
    <dashboard-wrapper>
        <page-header :caption="$route.meta.caption" />

        <div class="mt-3">
            <nav-tabs id="settings_nav-tab">
                <nav-tab-link name="profile" label="Профиль" class="active" />
                <nav-tab-link name="progress" label="Прогресс" />
            </nav-tabs>
            <nav-tab-content id="settings_nav-tabContent">
                <nav-tab-pane name="profile" class="active show">
                    <user-profile-fragment :user="user" />
                </nav-tab-pane>
                <nav-tab-pane name="progress">
                    <user-tasks-progress :id="user.id" />
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
import UserProfile from "@/modules/settings/components/UserProfile.vue";
import UserProfileFragment from "@/modules/users/components/UserProfileFragment.vue";
import UserTasksProgress from "@/modules/users/components/UserTasksProgress.vue";
import {mapGetters} from "vuex";


export default {
    name: "SettingsPage",
    components: {
        UserTasksProgress,
        UserProfileFragment,
        UserProfile, NavTabContent, NavTabs, NavTabPane, NavTabLink, PageHeader, DashboardWrapper},

    data() {
        return {
            profilePhoto: null,
            //user: null
        }
    },

    mounted() {
        //console.log(store.getters['authStore/user']);
        //this.user = store.getters['authStore/user'];
    },

    computed: {
        ...mapGetters({
            user: "authStore/user"
        })
    }

}
</script>

<style scoped>

</style>
