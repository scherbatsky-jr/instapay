import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

import dashboard from "./dashboard"
import products from './products'
import stores from "./stores"

const Register = () => import('@/pages/register/Register.vue')
const Login = () => import('@/pages/login/Index.vue')
const PaymentPlans = () => import('@/pages/register/PaymentPlans.vue')
const PlanSuccess = () => import('@/pages/register/PlanSuccess.vue')

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
          meta: {
            authenticated: false,
        },
          name: 'register',
          path: '/register'
        },
        {
          component: PaymentPlans,
          meta: {
            authenticated: true,
        },
          name: 'payment-plans',
          path: '/payment-plans'
        },
        {
          component: PlanSuccess,
          meta: {
            authenticated: true,
        },
          name: 'plan-success',
          path: '/plan-success/:plan'
        },
        ...dashboard,
        ...products,
        ...stores,
    ]
})

router.beforeEach((to, from, next) => {
    const store = useAuthStore();
    const meta = to.meta

    if (meta.authenticated === true && !store.isLoggedIn) {
      next({ name: "login" });
    } else if (meta.authenticated === false && store.isLoggedIn) {
      next({ name: "dashboard" });
    } else {
      next();
    }
  });

export default router;