export default [
    {
        path: '/tasks',
        name: 'tasks',
        component: () => import("./pages/TasksPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#file-earmark-text',
            caption: 'Задания',
            role: ['admin', 'user'],
            order: 2
        }
    },

    {
        path: '/tasks/:id',
        name: 'tasks.get',
        component: () => import("./pages/UserTaskPage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#file-earmark-text',
            caption: 'Задание',
            role: ['admin', 'user']
        }
    },

    {
        path: '/tasks/reports',
        name: 'tasks.reports',
        component: () => import("./pages/TaskReviewsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#file-earmark-text',
            caption: 'Проверка заданий',
            role: ['admin'],
            order: 3
        }
    },
];
