import OrderForm from "@/pages/orders/NewOrder.vue"

const routes = [
    {
        component: OrderForm,
        path: '/order/:orderId',
        name: 'new-order'
    }
]

export default routes
