export default [
    {
        path: '/posts',
        name: 'posts.index',
        component: () => import("./pages/PostsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#puzzle',
            caption: 'Сообщения',
            role: ['admin', 'user'],
            order: 2.5
        }
    },
    {
        path: '/posts/:id',
        name: 'posts.show',
        component: () => import("./pages/PostShowPage.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#puzzle',
            caption: 'Сообщение',
            role: ['admin', 'user'],
            order: -100
        }
    },
];
