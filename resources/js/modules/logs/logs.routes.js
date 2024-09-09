export default [
    {
        path: '/logs',
        name: 'logs',
        component: () => import("./pages/UsersActivityLogsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Активность',
            role: ['admin'],
            order: 10
        }
    },
];
