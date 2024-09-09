export default [
    {
        path: '/questions',
        name: 'questions',
        component: () => import("./pages/QuestionsListPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#graph-up',
            caption: 'Вопросы',
            role: ['admin'],
            order: 6
        }
    },
];
