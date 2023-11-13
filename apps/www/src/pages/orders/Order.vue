<template>
    <div>
        <div v-if="showForm">
            <Form @submit="submitForm" v-slot="{errors}">
                <div class="form-group">
                    <label for="tracking">Tracking Number</label>
                    <Field
                        v-model="trackingNumber"
                        class="form-control"
                        name="tracking"
                        id="tracking"
                        rules="required"
                    />
                    <span class="invalid-feedback" v-if="errors.tracking">{{  errors.tracking }}</span>
                </div>
                <button class="btn btn=primart" type="submit">Submit</button>
            </Form>
        </div>
        <div v-else>
            <h3>Your Order: {{ order.id }}</h3>
        </div>
    </div>
</template>

<script>
import { useOrderStore } from "@/stores/orders"

export default {
    name: "Order",

    created () {

    },

    data () {
        return {
            showForm: true,
            trackingNumber: null
        }
    },

    methods: {
        submitForm() {
            const store = useOrderStore()

            store.fetchOrders({
                key: 'tracking_number',
                value: this.trackingNumber
            })
            .then((orders) => {
                this.order = orders[0]

                this.showForm = false
            })
        }
    }
}
</script>