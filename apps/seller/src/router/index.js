import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import dashboard from "./dashboard"
import products from './products'
import stores from "./stores"


const Register = () => import('@/pages/register/Register.vue')
const Login = () => import('@/pages/login/Index.vue')
const PaymentPlans = () => import('@/pages/register/PaymentPlans.vue')

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            component: Login,
            meta: {
                authenticated: false,
            },
            name: "login",
            path: "/login",
        },
        {
          component: Register,
          name: 'register',
          path: '/register'
        },
        {
          component: PaymentPlans,
          name: 'payment-plans',
          path: '/paymentplans'
        },
        ...dashboard,
        ...products,
        ...stores,
    ]
})

router.beforeEach((to, from, next) => {
    const store = useAuthStore();
    const meta = to.meta
  
    if (typeof meta.feature !== "undefined" && !feature(meta.feature)) {
      next({ name: "login" });
    } else if (
      typeof meta.authenticated === "undefined" ||
      meta.authenticated === null ||
      (meta.authenticated === true && store.isLoggedIn) ||
      (meta.authenticated === false && !store.isLoggedIn)
    ) {
      next();
    } else if (meta.authenticated === true && !store.isLoggedIn) {
      next({ name: "login" });
    } else if (meta.authenticated === false && store.isLoggedIn) {
      next({ name: "login" });
    } else {
      next();
    }
  });

export default router;