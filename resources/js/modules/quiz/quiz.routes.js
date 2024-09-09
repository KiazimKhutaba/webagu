export default [
    {
        path: '/quizzes',
        name: 'quizzes',
        component: () => import("./pages/QuizzesPage.vue"),
        meta: {
            isSidebarItem: true,
            useSvg: '#door-closed',
            requiresAuth: true,
            caption: 'Тесты',
            role: ['admin', 'user'],
            order: 9
        }
    },

    {
        path: '/quiz/:id',
        name: 'quiz.show',
        component: () => import("./pages/QuizDisplayPage.vue"),
        meta: {
            requiresAuth: true,
            caption: 'Тест',
            isSidebarItem: false,
            useSvg: '#door-closed',
            role: ['admin', 'user']
        }
    },

    {
        path: '/quiz/:id/access-requests',
        name: 'quiz.access-requests',
        component: () => import("./pages/QuizAccessRequestsPage.vue"),
        meta: {
            requiresAuth: true,
            caption: 'Заявки на доступ к тесту',
            isSidebarItem: false,
            useSvg: '#door-closed',
            role: ['admin']
        }
    },

    {
        path: '/quizzes/passed',
        name: 'quizzes.passed-list',
        component: () => import('./pages/PassedQuizzesPage.vue'),
        meta: {
            requiresAuth: true,
            caption: 'Пройденные тесты',
            isSidebarItem: true,
            useSvg: '#door-closed',
            role: ['admin'],
            order: 10
        }
    },

    {
        path: '/quizzes/passed/:id',
        name: 'quizzes.passed',
        component: () => import('./pages/PassedQuizPage.vue'),
        meta: {
            requiresAuth: true,
            caption: 'Пройденный тест',
            isSidebarItem: false,
            useSvg: '#door-closed',
            role: ['admin']
        }
    },
];
