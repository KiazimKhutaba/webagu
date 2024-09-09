<template>
    <router-view :key="viewKey"></router-view>
</template>

<script>
import DashboardSvgs from "./common/components/DashboardSvgs.vue";
import AppHeader from "./common/components/AppHeader.vue";
import AppSidebar from "./common/components/AppSidebar.vue";
import {mapActions, mapState} from "vuex";


export default {
    name: "App",
    components: {AppSidebar, AppHeader, DashboardSvgs},
    async mounted() {
        if(this.user && this.isUserAuthenticated) {
            await this.getAndSetUnreadPostsCount();
            await this.initDarkMode();
        }
    },

    computed: {
        ...mapState('authStore', ['user', 'isUserAuthenticated']),

        viewKey() {
            // this is needed as on concrete page changing url query string - vue mounted hook is triggered
            // so to avoid unwanted network requests we change router view key
            if(this.$route.name === 'tasks.get') {
                return this.$route.path;
            }

            // this is needed in other cases, for example for pagination correct work
            return this.$route.fullPath;
        }
    },

    methods: {
        ...mapActions('appSettingsStore', ['getAndSetUnreadPostsCount', 'initDarkMode']),

    },

}
</script>

<style scoped>
.App {
    height: calc(100% - 55px);
}

.App > .row {
    height: 100%
}

@media screen and (max-width: 768px) {
    .App {
        height: auto;
    }

}
</style>
