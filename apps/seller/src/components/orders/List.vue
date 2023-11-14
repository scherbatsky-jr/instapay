<template>
    <div>
        <h3>Orders:</h3>
        <div class="spinner-border" v-if="loading"></div>
        <table v-else class="table table-striped">
            <thead>
                <th scope="col">S.N.</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Status</th>
                <th scope="col">Contact</th>
                <th scope="col" class="text-center">Tracking No.</th>
                <th scope="col" class="text-center">Date</th>
                <th scope="col" class="text-center">Items</th>
                <th scope="col" class="text-center">Action</th>
            </thead>
            <tbody>
                <tr v-for="order, index in orders">
                    <th scope="row">{{  index + 1 }}</th>
                    <td>{{  order.total_amount }}</td>
                    <td>{{  getStatus(order.status) }}</td>
                    <td>{{  order.deliveryAddress ? order.deliveryAddress.contact : '--' }}</td>
                    <td>{{  order.tracking_number ? order.tracking_number : '--' }}</td>
                    <td class="text-center">{{  order.created_at }}</td>
                    <td>
                        <ul>
                            <li v-for="item in order.items">{{ item.product.title }}</li>
                        </ul>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-primary" @click="copyOrderLink(order)">
                            Copy Order Link
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { useOrderStore } from "@/stores/orders"

export default {
    name: 'OrderList',

    created () {
        this.prepareComponent();
    },

    data () {
        return {
            loading: false,
            orders: []
        }
    },

    methods: {
        prepareComponent () {
            this.loading = true
            const store = useOrderStore()

            store.fetchOrders()
                .then(orders => {
                    this.orders = orders
                })
                .finally(() => {
                    this.loading = false
                })
        },

        copyOrderLink (order) {
            const orderLink = `${this.$appConfig.wwwUrl}\\order\\${order.id}`
            console.log(orderLink)

            navigator.clipboard.writeText(orderLink);
        },

        getStatus(status) {
            switch (status) {
                case 1:
                    return "Payment Pending"
                    break;
                case 2:
                    return "Payment Success"
                    break;
                case 3:
                    return "Shipped"
                    break;
                case 4:
                    return "Delivered"
                    break
                default:
                    return "New Order"
                    break;
            }
        }
    }
}
</script>