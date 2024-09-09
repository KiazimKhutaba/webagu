export default [
    {
        path: '/users',
        name: 'users',
        component: () => import("./pages/UsersPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Студенты',
            role: ['admin'],
            order: 4
        }
    },

    {
        path: '/@:username',
        name: 'user.public',
        component: () => import("./pages/UserPublicPage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Студент',
            role: ['admin', 'user'],
            order: -1
        }
    },

    {
        path: '/user/:id/profile',
        name: 'user.profile',
        component: () => import("./pages/UserProfilePage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Cтудент',
            role: ['admin']
        },
    },
];
