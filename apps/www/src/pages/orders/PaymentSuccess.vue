<template>
    <div class="page">
        <div class="spinner-border" v-if="loading"></div>
        <div v-else class="d-flex flex-column">
            <p class="text-success"><strong>Your payment for order no. {{ order.id }} has been succesful.</strong></p>
            <p>The tracking number for your order is: <strong>{{ order.tracking_number }}</strong>.
            <strong>Please copy and save this number to track your order</strong>
        </p>
            <p>Click <a href="/track/order">here</a> to go the order page to track your order</p>

            <div class="d-flex flex-column message">
                <h1>Thank You for using our service.</h1>
                <Icon icon="lets-icons:check-fill" class="icon"/>
            </div>
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
            this.loading = true;
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

<style lang="scss">
.page {
    width: 100%;

    .message {
        margin: 5rem auto;
    }

    .icon {
        height: 15rem;
        width: 15rem;
        color: green;
        margin: auto;
    }
}
</style>