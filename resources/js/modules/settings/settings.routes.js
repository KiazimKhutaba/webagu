export default [
    {
        path: '/settings',
        name: 'settings',
        component: () => import("./pages/SettingsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#gear-wide-connected',
            caption: 'Профиль',
            role: ['admin', 'user'],
            order: 7
        }
    },
];
