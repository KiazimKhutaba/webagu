export default [
    {
        path: '/signup',
        name: 'signup',
        component: () => import("./pages/CreateAccountPage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Вход на сервис',
            useSvg: '#house-fill',
            role: ['user', 'admin']
        }
    },
    {
        path: '/',
        name: 'login',
        component: () => import("./pages/LoginPage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Login',
            role: ['admin', 'user']
        }
    },
    {
        path: '/password-reset',
        name: 'requests.password_change',
        component: () => import("./pages/PasswordChangeRequestForm.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Заявка на смену пароля',
            role: ['admin', 'user']
        }
    },
    {
        path: '/requests',
        name: 'account.requests',
        component: () => import("./pages/PasswordChangeRequestsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Заявки на смену пароля',
            useSvg: '#file-earmark-text',
            role: ['admin'],
            order: 100
        }
    },
];
