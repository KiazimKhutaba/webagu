export const openFormsRoutes = [
    {
        path: '/openforms/form-create',
        name: 'openforms.formcreate',
        component: () => import("./pages/FormCreationPage.vue"),
        exact: true,
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Новая форма',
            useSvg: '',
            role: ['admin'],
            order: 101
        }
    },
    {
        path: '/openforms/formslist',
        name: 'openforms.formslist',
        component: () => import("./pages/FormsListPage.vue"),
        exact: true,
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Формы (тесты)',
            useSvg: '#house-fill',
            role: ['admin', 'user'],
            order: 100
        }
    },
    {
        path: '/openforms/form/:id',
        name: 'openforms.formshow',
        component: () => import("./pages/FormShowPage.vue"),
        exact: true,
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Форма',
            useSvg: '#house-fill',
            role: ['admin', 'user'],
            order: 102
        }
    },
    {
        path: '/openforms/quiztakes',
        name: 'openforms.quiztakes',
        component: () => import("./pages/QuizTakesPage.vue"),
        exact: true,
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Сданные формы',
            useSvg: '#house-fill',
            role: ['admin', 'user'],
            order: 100
        }
    },
    {
        path: '/openforms/quiztake/:id',
        name: 'openforms.quiztake',
        component: () => import("./pages/QuizTakePage.vue"),
        exact: true,
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Сданная форма',
            useSvg: '#house-fill',
            role: ['admin', 'user'],
            order: 102
        }
    },
];
