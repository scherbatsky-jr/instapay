import Stores from "@/pages/stores/Index.vue"
import Add from "@/pages/stores/components/Add.vue"
import List from "@/pages/stores/components/List.vue"
import Show from "@/pages/stores/components/Show.vue"
import AddProduct from "@/pages/products/Add.vue"

const routes = [
    {
        component: Stores,
        name: "stores",
        path: "/stores",
        children: [
            {
                component: Add,
                name: "add-store",
                path: "add",
            },
            {
                component: List,
                name: 'stores',
                path: ''
            },
            {
                component: Show,
                name: 'show-store',
                path: ':storeId',
            }
        ],
    }
]

export default routes;