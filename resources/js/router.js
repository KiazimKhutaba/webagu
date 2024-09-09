import {createRouter, createWebHistory} from "vue-router";
import store from "./store/index.js";
import {openFormsRoutes} from "./modules/openForms/openForms.routes.js";
import accountRoutes from "./modules/account/account.routes.js";
import filesRoutes from "./modules/files/files.routes.js";
import lectureRoutes from "./modules/lecture/lecture.routes.js";
import logsRoutes from "./modules/logs/logs.routes.js";
import postsRoutes from "./modules/posts/posts.routes.js";
import questionsRoutes from "./modules/questions/questions.routes.js";
import quizRoutes from "./modules/quiz/quiz.routes.js";
import reportsRoutes from "./modules/reports/reports.routes.js";
import settingsRoutes from "./modules/settings/settings.routes.js";
import taskRoutes from "./modules/task/task.routes.js";
import usersRoutes from "./modules/users/users.routes.js";

const routes = [

    {
        path: '/forbidden',
        name: 'forbidden',
        component: () => import("./common/pages/Forbidden.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Forbidden',
            role: ['admin', 'user']
        }
    },

    {
        path: '/imageviewer/:image',
        name: 'imageviewer',
        // Todo: throws error because Promise should be returned
        component: () => import('./common/pages/ImageViewerPage.vue'),
        meta: {
            isSidebarItem: false,
            useSvg: '#door-closed',
            requiresAuth: true,
            caption: 'Просмотр изображения',
            role: ['admin', 'user'],
            order: -1
        }
    },
    {
        path: '/logout',
        name: 'logout',
        // Todo: throws error because Promise should be returned
        component: async () => {
            await store.dispatch('authStore/logout').then(() => {

                setTimeout(() => {
                    // router.push({ name: 'login' });
                    // localStorage.setItem('weblearning__store', null);
                    window.location.href = '/';
                }, 100)

               // window.location.reload();
            });

            return { template: '<p>Logout</p>' };
            // return import('../pages/Forbidden.vue');
        },
        meta: {
            isSidebarItem: true,
            useSvg: '#door-closed',
            requiresAuth: true,
            caption: 'Выйти',
            role: ['admin', 'user'],
            order: 8
        }
    },

    // https://router.vuejs.org/guide/migration/#removed-star-or-catch-all-routes
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        meta: {
            requiresAuth: false,
        },
        component: () => import("./common/pages/NotFoundPage.vue")
    },
];


const
    router = createRouter({
    history: createWebHistory(''),
    routes: [
        ...routes,
        ...openFormsRoutes,
        ...accountRoutes,
        ...filesRoutes,
        ...lectureRoutes,
        ...logsRoutes,
        ...postsRoutes,
        ...questionsRoutes,
        ...quizRoutes,
        ...reportsRoutes,
        ...settingsRoutes,
        ...taskRoutes,
        ...usersRoutes,
    ]
});

router.beforeEach((to, from, next) => {

    const { user, isUserAuthenticated } = store.state.authStore;

    // Todo: add here create account filtering
    if(isUserAuthenticated && to.matched.some(r => ['login', 'signup', 'requests.password_change'].includes(r.name))) {
        return next({ name: 'dashboard' });
    }

    if (to.matched.some((route) => route.meta?.requiresAuth))
    {
        if (isUserAuthenticated)
        {
            // console.log('matched', to.matched);

            if( to.meta?.role.includes(user.role) )
                next()
            else
                next({ name: 'forbidden' })
        } else {
            next({ name: 'login' })
        }
    } else {
        next()
    }
});

export default router;
