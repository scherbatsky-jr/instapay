import OrderForm from "@/pages/orders/NewOrder.vue"
import PaymentSuccess from "@/pages/orders/PaymentSuccess.vue"
import Order from "@/pages/orders/Order.vue"

const routes = [
    {
        component: OrderForm,
        path: '/order/:orderId',
        name: 'new-order'
    },
    {
        component: PaymentSuccess,
        path: '/order/payment_success/:orderId',
        name: 'payment_success'
    },
    {
        component: Order,
        path: '/track/order',
        name: 'track_order'
    }
]

export default routes
