import { createWebHistory, createRouter } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('./pages/home.vue'),
    },
    {
        path: '/entry',
        name: 'Entry',
        component: () => import('./pages/entry.vue'),
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('./pages/login.vue'),

    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('./pages/register.vue'),

    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// router.beforeEach((to,from) =>{
//     if(to.meta.requiresAuth && !localStorage.getItem('token')){
//         return { name:'Login' }
//     }
//     if(to.meta.requiresAuth == false && localStorage.getItem('token')){
//         return { name:'Dashboard' }
//     }
// })

export default router;
