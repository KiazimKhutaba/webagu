export default [
    {
        path: '/files',
        name: 'files',
        component: () => import("./pages/UserFilesPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Файлы',
            role: ['admin'],
            order: 5
        }
    },
];
