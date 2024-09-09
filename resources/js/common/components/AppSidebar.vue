<template>
    <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">

        <dashboard-svgs/>

        <div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebarMenu"
             aria-labelledby="sidebarMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarMenuLabel">WebAgu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                <ul class="nav flex-column">

                    <li class="nav-item" v-for="route in firstRoutes" :key="route.name">
                        <router-link :class="{'nav-link d-flex align-items-center gap-2': true, 'active': isActivePage(route.name) }" aria-current="page"
                                     :to="route.path">
                            <svg class="bi">
                                <use v-bind="{'xlink:href': route.meta.useSvg }"></use>
                            </svg>
                            {{ route.meta.caption }}
                            <span class="badge bg-success d-inline" v-if="showUnreadPostsCounter(route)">{{ unreadPostsCount }}</span>
                        </router-link>
                    </li>
                </ul>

                <hr class="my-3">

                <ul class="nav flex-column mb-auto">

                    <li class="nav-item">
                        <span class="nav-link">
                            <switcher v-model:checked="isDarkMode"  id="darkModeSwitcher" class="d-flex align-items-center">
                                <div class="ms-2" style="margin-top: 4px;">
                                    {{ isDarkMode ? 'Ночной режим' : 'Дневной режим' }}
                                </div>
                            </switcher>
                        </span>
                    </li>

                    <li class="nav-item" v-for="route in secondRoutes" :key="route.name">
                        <router-link :class="{'nav-link d-flex align-items-center gap-2': true, 'active': isActivePage(route.name) }" :to="`/${route.name}`">
                            <svg class="bi">
                                <use v-bind="{'xlink:href': route.meta.useSvg }"></use>
                            </svg>
                            {{ route.meta.caption }}
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import DashboardSvgs from "./DashboardSvgs.vue";
import router from "../../router.js";
import {mapActions, mapGetters, mapState} from "vuex";
import Switcher from "./Switcher.vue";

export default {
    name: "AppSidebar",
    components: {Switcher, DashboardSvgs},
    data() {
        return {
            firstRoutes: [],
            secondRoutes: []
        }
    },
    computed: {
        ...mapGetters('authStore', ['user']),
        ...mapState('appSettingsStore', ['unreadPostsCount', 'isDarkModeEnabled']),

        isDarkMode: {
            get() {
                return this.isDarkModeEnabled;
            },
            set(value) {
                this.setDarkMode(value);
            }
        }
    },

    mounted() {

        const routes = router.getRoutes();

        this.firstRoutes = routes.filter(route => {
            if (
                route && route.name &&
                route.meta && route.meta.isSidebarItem &&
                !['logout', 'settings'].includes(route.name) &&
                route.meta.role.includes(this.user.role)
            ) {
                return route;
            }
        });

        this.firstRoutes.sort(this.compareRoutesOrder);

        this.secondRoutes = routes.filter(route => {
                if (route && route.meta && route.meta.isSidebarItem && ['logout', 'settings'].includes(route.name))
                    return route;
            }
        );

        this.secondRoutes.sort(this.compareRoutesOrder);
    },

    methods: {

        ...mapActions('appSettingsStore', ['setDarkMode']),

        showUnreadPostsCounter(route) {
            return route.name === 'posts.index' && this.unreadPostsCount > 0;
        },

        /**
         *
         * @param r1 {RouteRecord}
         * @param r2 {RouteRecord}
         * @return {number}
         */
        compareRoutesOrder(r1, r2) {
            if ( r1.meta.order < r2.meta.order ){
                return -1;
            }
            if ( r1.meta.order > r2.meta.order ){
                return 1;
            }
            return 0;
        },

        isActivePage(route_name) {
            //console.log('address_line', this.$route.name, 'page_route_name', route_name);
            return this.$route.name === route_name;
        },
    }
}
</script>

<style scoped>

.nav {
    --bs-nav-link-color: var(--bs-success);
    --bs-nav-link-hover-color: var(--bs-success);
    --bs-nav-link-disabled-color: var(--bs-secondary-color);
}

.active {
    background: var(--bs-success-bg-subtle);
    color: var(--bs-success) !important;
}

[data-bs-theme="dark"] .nav {
    --bs-nav-link-color: #fff;
    --bs-nav-link-hover-color: #fff;
}

[data-bs-theme="dark"] .active {
    background: #959595;
    color: #fff !important;
}

</style>
