<template>
    <div>
        <div class="spinner-border" v-if="loading"></div>
        <table v-else>
            <thead>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Items</th>
                <th>Action</th>
            </thead>
            <tbody>
                <tr v-for="order in orders">
                    <td>{{  order.total_amount }}</td>
                    <td>{{  order.status }}</td>
                    <td>{{  order.created_at }}</td>
                    <td>
                        <ul>
                            <li v-for="item in order.items">{{ item.product_id }}</li>
                        </ul>
                    </td>
                    <td>
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
        }
    }
}
</script>