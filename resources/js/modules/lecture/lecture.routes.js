export default [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import("./pages/LecturesPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Материалы',
            useSvg: '#house-fill',
            role: ['user', 'admin'],
            order: 1
        }
    },
    {
        path: '/lecture/:id',
        name: 'lecture',
        component: () => import("./pages/LecturePage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#graph-up',
            caption: 'Лекции',
            role: ['user', 'admin']
        }
    },
];
