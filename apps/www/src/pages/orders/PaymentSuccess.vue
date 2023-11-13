<template>
    <div>
        <div class="spinner-border" v-if="loading"></div>
        <div v-else>
            <p>Your payment for order no. {{ order.id }} has been succesful.</p>
            <p>The tracking number for your order is: {{ order.tracking_number }}. Please copy and save this number to track your order</p>
            <p>Click here to go the order page to track your order</p>
        </div>
    </div>
</template>

<script>
import { useOrderStore } from "@/stores/orders"
export default {
    name: 'PaymentSuccess',
    
    created () {
        this.prepareComponent()
    },

    data () {
        return {
            order: {},
            loading: false
        }
    },

    methods: {
        prepareComponent () {
            const orderId = parseInt(this.$route.params.orderId)
            const store = useOrderStore()

            store.updateOrder({
                id: orderId,
                status: 2,
                addressInfo: null,
                tracking_number: `${orderId}${new Date().getTime()}`
            })
            .then((order) => {
                this.order = order
            })
            .finally(() => {
                this.loading = false
            })
        }
        
    }
}
</script>
