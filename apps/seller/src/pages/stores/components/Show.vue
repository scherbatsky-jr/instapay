<template>
    <div class="m-4 p-store">
        <div class="spinner-border" role="status" v-if="loading"></div>
        <div v-else class="d-flex flex-column">
            <h2><Icon icon="healthicons:market-stall-outline" class="icon"/>{{ store.name }}</h2>
            <div class="m-3">
                <div class="d-flex header mb-3">
                    <h4>
                        Products
                    </h4>
                    <button
                        @click="addProduct()"
                        class="btn btn-primary"
                    >
                        Add a product
                    </button>
                </div>
                <table class="table table-striped">
                    <thead>
                        <th>S.N.</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Brand</th>
                        <th scope="col" class="text-center">Actions</th>
                    </thead>
                    <tbody>
                        <tr v-for="product, index in products">
                            <th scope="row">{{  index + 1 }}</th>
                            <td>{{  product.title }}</td>
                            <td>{{  product.description }}</td>
                            <td>{{  product.price }}</td>
                            <td>{{  product.stock }}</td>
                            <td>{{  product.brand ? product.brand : '--' }}</td>
                            <td class="text-center">
                                <button @click="createOrder()" class="btn btn-primary">Create an Order</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <OrderList class="m-3"/>


            <BModal v-model="showOrderPopup" hideHeader hideFooter>
                <h3>Create an Order</h3>
                <OrderForm :products="products" ref="orderForm"/>
                <div class="d-flex mt-4 justify-content-between">
                    <button class="btn btn-danger">Cancel</button>
                    <button class="btn btn-primary" @click="onCreateOrder()">Create</button>
                </div>
            </BModal>
        </div>
    </div>
</template>

<script>
import { useStoreStore } from '../../../stores/stores';
import { useProductStore } from "@/stores/products";
import { useOrderStore } from "@/stores/orders";
import { useAuthStore } from "@/stores/auth";

import OrderForm from "@/components/orders/OrderForm.vue"
import OrderList from "@/components/orders/List.vue"

export default {
    name: 'Store',

    created () {
        this.prepareComponent()
    },

    components: {
        OrderForm,
        OrderList
    },

    data() {
        return {
            loading: false,
            products: [],
            store: {},
            showOrderPopup: false
        }
    },

    methods: {
        addProduct () {
            this.$router.push({ name: 'add-product', params: { storeId: this.store.id }})
        },

        getProducts() {
            const filters = {
                key: 'store_id',
                value: this.store.id
            }

            const sort = {
                key: 'created_at',
                direction: "DESC"
            }

            const productStore = useProductStore()

            productStore.fetchProducts(filters, sort)
                .then((products) => {
                    this.products = products
                })
                .finally(() => {
                    this.loading = false
                })

        },

        createOrder() {
            this.showOrderPopup = true
        },

        onCreateOrder() {
            const order = this.$refs.orderForm.form;
            const orderStore = useOrderStore();
            const authStore = useAuthStore()

            order.store_id = this.store.id;
            order.created_by = authStore.getUser.id;
            order.total_amount = this.$refs.orderForm.totalAmount;

            console.log(order)

            orderStore.createOrder(order)
                .then(order => {
                    window.location.reload();
                })

        },

        prepareComponent () {
            this.loading = true;
            const store = useStoreStore()

            const storeId = parseInt(this.$route.params.storeId)

            store.getStoreById(storeId)
                .then((store) => {
                    this.store = store

                    this.getProducts();
                })
        }
    }


}
</script>

<style lang="scss">
.p-store {
    width: 100%;

    .header {
        justify-content: space-between;
    }
}
</style>