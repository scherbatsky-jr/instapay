<template>
    <div class="spinner-border" v-if="loading"></div>
    <div v-else>
        <h1>Checkout Your Order</h1>
        <div>
            <h3>Items in your Order</h3>
            <div v-for="item in order.items">
                <h5>{{ item.product.title }}</h5>
                <p>{{ item.product.description }}</p>
                <p>{{ 'Number of items: ' + item.count  }}</p>
                <p>{{ 'Total Amount: ' + (item.count * item.product.price) }}</p>
            </div>
            <h5>Total Price: {{ order.total_amount }}</h5>
        </div>
        <div>
            <h3>Please fill in your delivery information</h3>
                <Form @submit="saveDeliveryInfo" v-slot="{ errors }">
                <!-- First Name Field -->
                <div class="mb-3">
                <label for="firstName" class="form-label">First Name:</label>
                <Field
                    v-model="addressInfo.first_name"
                    name="firstName"
                    id="firstName"
                    rules="required"
                    :class="{ 'is-invalid': errors.firstName }"
                    class="form-control"
                />
                <div v-if="errors.firstName" class="invalid-feedback">{{ errors.firstName }}</div>
                </div>

                <!-- Last Name Field -->
                <div class="mb-3">
                <label for="lastName" class="form-label">Last Name:</label>
                <Field
                    v-model="addressInfo.last_name"
                    name="lastName"
                    id="lastName"
                    rules="required"
                    :class="{ 'is-invalid': errors.lastName }"
                    class="form-control"
                />
                <div v-if="errors.lastName" class="invalid-feedback">{{ errors.lastName }}</div>
                </div>

                <!-- Contact No. Field -->
                <div class="mb-3">
                <label for="contactNo" class="form-label">Contact No.:</label>
                <Field
                    v-model="addressInfo.contact"
                    name="contactNo"
                    id="contactNo"
                    rules="required"
                    :class="{ 'is-invalid': errors.contactNo }"
                    class="form-control"
                />
                <div v-if="errors.contactNo" class="invalid-feedback">{{ errors.contactNo }}</div>
                </div>

                <!-- Street Field -->
                <div class="mb-3">
                <label for="street" class="form-label">Street:</label>
                <Field
                    v-model="addressInfo.street"
                    name="street"
                    id="street"
                    rules="required"
                    :class="{ 'is-invalid': errors.street }"
                    class="form-control"
                />
                <div v-if="errors.street" class="invalid-feedback">{{ errors.street }}</div>
                </div>

                <!-- Area Field -->
                <div class="mb-3">
                <label for="area" class="form-label">Area:</label>
                <Field
                    v-model="addressInfo.area"
                    name="area"
                    id="area"
                    rules="required"
                    :class="{ 'is-invalid': errors.area }"
                    class="form-control"
                />
                <div v-if="errors.area" class="invalid-feedback">{{ errors.area }}</div>
                </div>

                <!-- City Field -->
                <div class="mb-3">
                <label for="city" class="form-label">City:</label>
                <Field
                    v-model="addressInfo.city"
                    name="city"
                    id="city"
                    rules="required"
                    :class="{ 'is-invalid': errors.city }"
                    class="form-control"
                />
                <div v-if="errors.city" class="invalid-feedback">{{ errors.city }}</div>
                </div>

                <!-- State Field -->
                <div class="mb-3">
                <label for="state" class="form-label">State:</label>
                <Field
                    v-model="addressInfo.state"
                    name="state"
                    id="state"
                    rules="required"
                    :class="{ 'is-invalid': errors.state }"
                    class="form-control"
                />
                <div v-if="errors.state" class="invalid-feedback">{{ errors.state }}</div>
                </div>

                <!-- Landmarks Field -->
                <div class="mb-3">
                <label for="landmarks" class="form-label">Landmarks:</label>
                <Field
                    v-model="addressInfo.landmarks"
                    name="landmarks"
                    id="landmarks"
                    rules="required"
                    :class="{ 'is-invalid': errors.landmarks }"
                    class="form-control"
                />
                <div v-if="errors.landmarks" class="invalid-feedback">{{ errors.landmarks }}</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
            </Form>
        </div>

        <div v-if="showPaymentOptions">
            <h3>Choose your payment method: </h3>
        </div>
    </div>
</template>

<script>
import { useOrderStore } from "@/stores/orders"
export default {
    name: 'OrderForm',

    created () {
        this.prepareComponent();
    },

    data () {
        return {
            loading: false,
            order: {},
            addressInfo: {
                first_name: null,
                last_name: null,
                contact: null,
                street: null,
                area: null,
                city: null,
                state: null,
                landmarks: null
            },
            showPaymentOptions: false
        }
    },

    methods: {
        prepareComponent() {
            this.loading = true
            const store = useOrderStore()
            const orderId = parseInt(this.$route.params.orderId)

            store.getOrderById(orderId)
                .then(order => {
                    this.order = order

                    if (this.order.deliveryAddress) {
                        this.addressInfo = order.deliveryAddress
                    }
                })
                .finally(() => {
                    this.loading = false
                })
        },

        saveDeliveryInfo () {
            const store = useOrderStore();

            const orderInput = {
                id: this.order.id,
                status: 1,
                addressInfo: this.addressInfo
            }

            store.updateOrder(orderInput)
                .then(order => {
                    this.order = order;

                    this.showPaymentOptions = true;
                })
        }
    }
}
</script>