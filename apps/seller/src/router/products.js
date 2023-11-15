import AddProduct from "@/pages/products/Add.vue"
import Show from "@/pages/products/Show.vue"

const routes = [
    {
        component: AddProduct,
        meta: {
            authenticated: true,
        },
        name: 'add-product',
        path: '/store/:storeId/products/add'
    },
    {
        component: Show,
        meta: {
            authenticated: true,
        },
        name: 'show-product',
        path: '/store/:storeId/products/:productId'
    }
]

export default routes;
