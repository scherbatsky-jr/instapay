import { createRouter, createWebHistory } from "vue-router";
// import { useAuthStore } from "@/stores/auth";

import orders from './orders'

// const Login = () => import('@/pages/login/Index.vue')

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        // {
        //     component: Login,
        //     meta: {
        //         authenticated: false,
        //     },
        //     name: "login",
        //     path: "/login",
        // },
        // ...dashboard,
        // ...products,
        // ...stores,
        ...orders
    ]
})

// router.beforeEach((to, from, next) => {
//     const store = useAuthStore();
//     const meta = to.meta
  
//     if (typeof meta.feature !== "undefined" && !feature(meta.feature)) {
//       next({ name: "home" });
//     } else if (
//       typeof meta.authenticated === "undefined" ||
//       meta.authenticated === null ||
//       (meta.authenticated === true && store.isLoggedIn) ||
//       (meta.authenticated === false && !store.isLoggedIn)
//     ) {
//       next();
//     } else if (meta.authenticated === true && !store.isLoggedIn) {
//       next({ name: "login" });
//     } else if (meta.authenticated === false && store.isLoggedIn) {
//       next({ name: "home" });
//     } else {
//       next();
//     }
//   });

export default router;