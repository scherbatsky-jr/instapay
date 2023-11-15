import Dashboard from "@/pages/dashboard/Index.vue"

const routes = [
    {
        component: Dashboard,
        meta: {
            authenticated: true,
        },
        name: "dashboard",
        path: "/dashboard"
    }
]

export default routes