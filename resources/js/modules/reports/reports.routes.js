export default [
    {
        path: '/reports',
        name: 'reports',
        component: () => import("./pages/ReportsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#file-earmark-text',
            caption: 'Отчеты',
            role: ['admin'],
            order: 100,
        }
    },
];
