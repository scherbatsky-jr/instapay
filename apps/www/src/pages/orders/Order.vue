<template>
    <div class="d-flex w-100">
        <div v-if="showForm" class="track-form">
            <h3>Track your Order</h3>
            <p>Enter your order tracking number below to track your order in real time.</p>
            <Form @submit="submitForm" v-slot="{ errors }">
                <div class="form-group mb-3">
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
                <button class="btn btn-primary" type="submit">Submit</button>
            </Form>
        </div>
        <div v-else>
            <h3>Your Order: #{{ order.id }}</h3>
            <div class="d-flex w-100">
                <div>
                    <h3>Items in your Order</h3>
                    <div v-for="item in order.items" class="card order-item">
                        <div class="image">
                            <img :src="getImagePath(item)" />
                        </div>
                        <div class="p-2">
                            <h5>{{ item.product.title }}</h5>
                            <p>{{ item.product.description }}</p>    
                        </div>
                        <p>{{ 'Quantity: ' + item.count  }}</p>
                        <p>{{ 'Total Amount: ' + (item.count * item.product.price) }}</p>
                    </div>
                    <h4>Total Price: {{ order.total_amount }}</h4>
                </div>
                <div class="delivery-address">
                    <h3>Customer Information</h3>
                    <div><strong>{{ `${order.deliveryAddress.first_name} ${order.deliveryAddress.last_name}` }}</strong></div>
                    <div>{{ order.deliveryAddress.contact }}</div>
                    <div>{{ order.deliveryAddress.email }}</div>

                    <div class="mt-2"><strong>Address</strong></div>
                    <div>{{ `${order.deliveryAddress.street}, ${order.deliveryAddress.area}, ${order.deliveryAddress.city}`  }}</div>                    
                    <div>{{ order.deliveryAddress.state }}</div>
                    <div>
                        <p><strong>Landmarks</strong>: {{ order.deliveryAddress.landmarks }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-column">
                <h3>Order Status</h3>
                <p>{{ orderStatusMessage }}</p>
                <div class="card flex-row order-status">
                    <div class="status" :class="getStatusClass(1)">
                        <Icon icon="zondicons:add-solid" />
                        <span>Order Created</span>
                    </div>
                    <div v-for="n in [1, 2, 3, 4, 5, 6]" class="dots" :class="getDotsClass(2)"></div>
                    <div class="status" :class="getStatusClass(2)">
                        <Icon icon="lets-icons:check-fill" />
                        <span>Payment Success</span>
                    </div >
                    <div v-for="n in [1, 2, 3, 4, 5, 6]" class="dots" :class="getDotsClass(3)"></div>
                    <div class="status" :class="getStatusClass(3)">
                        <Icon icon="streamline:transfer-van-solid" />
                        <span>Shipped</span>
                    </div>
                    <div v-for="n in [1, 2, 3, 4, 5, 6]" class="dots" :class="getDotsClass(4)"></div>
                    <div class="status" :class="getStatusClass(4)">
                        <Icon icon="mdi:package-variant-closed-delivered" />
                        <span>Delivered</span>
                    </div>
                </div>
            </div>
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

    computed: {
        orderStatusMessage () {
            switch (this.order.status) {
                case 2:
                    return 'The payment for this order has been successful. We will ship your order soon.'
                case 3:
                    return "Your order has beens shipped! You will recieve your package in one or two days."
                case 4:
                    return "Your package has been delivered! Thank you for using our service!"
            }
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
        },

        getStatusClass(status) {
            if (status <= this.order.status) {
                return 'status--green'
            }
        },

        getDotsClass(status) {
            if (status <= this.order.status) {
                return 'dots--green'
            }
        },

        getImagePath(item) {
            const url = item.product.images ? item.product.images[0] : ''

            url = url.replace('instapay-minio', 'localhost');

            const imageUrl = url.split('?')[0];

            return imageUrl
        }
    }
}
</script>

<style lang="scss">
.track-form {
    margin: 3rem auto;
}

.order-item {
    padding: 1rem;
    width: 40rem;
    display: grid !important;
    grid-template-columns: 1fr 3.5fr 2fr 2fr;
    align-items: center;
    margin-bottom: 3rem;

    .image {
        border: 1px solid grey;
        width: 5rem;
        height: 5rem;
    }
}

.delivery-address {
    margin-left: 5rem;
}

.order-status {
    align-items: center;
    margin: 1rem auto;
    .status {
        display: flex;
        flex-direction: column;
        text-align: center;
        color: grey;

        svg {
            height: 5rem;
            margin: auto;
            width: 5rem;
            color: grey;
        }

        span {
            font-size: 1.25rem;
        }

        &--green {
            svg {
                color: green;
            }
        }
    }

    .dots {
        border-radius: 50%;
        background-color: grey;
        height: 1rem;
        width: 1rem;
        margin: 0 1rem;

        &--green {
            background-color: green;
        }
    }
}
</style>