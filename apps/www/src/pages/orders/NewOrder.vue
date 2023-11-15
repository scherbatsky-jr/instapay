<template>
    <div class="spinner-border" v-if="loading"></div>
    <div v-else class="d-flex flex-column p-order">
        <h1 class="mb-4">Checkout Your Order</h1>
        <div class="d-flex justify-content-between">
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
                    <button type="submit" class="btn btn-primary" :disabled="disableSubmit">Proceed to Payment</button>
                </Form>
            </div>

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
                    <div>
                        <p>Quantity</p>
                        <p>{{ item.count }}</p>
                    </div>
                    <div>
                        <p>Total Amount</p>
                        <p>{{ item.count * item.product.price }}</p>
                    </div>
                </div>
                <h5>Total Price: {{ order.total_amount }}</h5>
            </div>
        </div>

        <BModal v-model="showPaymentOptions" hideHeader hideFooter>
            <div>
                <h3>Choose your payment method: </h3>
                    <button @click="onPayEsewa">Pay with Esewa</button>
            </div>
        </BModal>
    </div>
</template>

<script>
import { useOrderStore } from "@/stores/orders"
import hmacSHA256 from "crypto-js/hmac-sha256"
import Base64 from 'crypto-js/enc-base64';
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
            showPaymentOptions: false,
            disableSubmit: false
        }
    },

    methods: {
        prepareComponent() {
            const hash = hmacSHA256("total_amount=110,transaction_uuid=ab14a8f2b02c3,product_code=EPAYTEST", "8gBm/:&EnhH.1/q");
            const hashInBase64 = Base64.stringify(hash);
            console.log(hashInBase64)
            this.loading = true
            const store = useOrderStore()
            const orderId = parseInt(this.$route.params.orderId)

            store.getOrderById(orderId)
                .then(order => {
                    this.order = order

                    if (this.order.status != 0) {
                        this.$router.push({name: 'track_order'})
                    }

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

            this.disableSubmit = true
            store.updateOrder(orderInput)
                .then(order => {
                    this.order = order;

                    this.showPaymentOptions = true;
                })
                .finally(() => {
                    this.disableSubmit = false
                })
        },

        generatePID() {
            const pid = (this.order.id * this.order.total_amount)

            return `${pid}-${new Date().toISOString()}`
        },

        onPayEsewa () {
            var path="https://uat.esewa.com.np/epay/main";
            var params= {
                amt: this.order.total_amount,
                psc: 0,
                pdc: 0,
                txAmt: 0,
                tAmt: this.order.total_amount,
                pid: this.generatePID(),
                scd: "EPAYTEST",
                su: `http://localhost:20182/order/payment_success/${this.order.id}`,
                fu: `http://localhost:20182/order/${this.order.id}`
            }
            var form = document.createElement("form");
            form.setAttribute("method", "POST");
            form.setAttribute("action", path);

            for(var key in params) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);
                form.appendChild(hiddenField);
            }

            document.body.appendChild(form);
            form.submit();
        },

        getImagePath(item) {
            let url = item.product.images ? item.product.images[0].url : ''

            url = url.replace('instapay-minio', 'localhost');

            const imageUrl = url.split('?')[0];

            return imageUrl
        }
    }
}
</script>

<style lang="scss">
.p-order {
    width: 100%;

    .order-item {
        align-items: center;
        padding: 1rem;
        width: 40rem;
        display: grid;
        grid-template-columns: 1fr 2fr 1fr 1fr;
        align-items: center;
        margin-bottom: 3rem;
        grid-gap: 1rem;

        .image {
            border: 1px solid grey;

            img {
                width: 6rem;
                height: 6rem;
            }
        }
    }
}
</style>