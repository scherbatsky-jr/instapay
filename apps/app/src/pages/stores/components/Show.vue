<template>
    <div class="spinner-border" role="status" v-if="loading"></div>
    <div v-else>
        <h2>{{ store.name }}</h2>
        <div>
            <h4>Your store products</h4>
            <table>
                <thead>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <tr v-for="product in products">
                        <td>{{  product.title }}</td>
                        <td>{{  product.price }}</td>
                        <td>{{  product.stock }}</td>
                        <td>
                            <button @click="createOrder()" class="btn btn-primary">Create an Order</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button
                @click="addProduct()"
                class="btn btn-primary"
            >
                Add a products
            </button>
        </div>
    </div>
</template>

<script>
import { useStoreStore } from '../../../stores/stores';
import { useProductStore } from "@/stores/products";

export default {
    name: 'Store',

    created () {
        this.prepareComponent()
    },

    data() {
        return {
            loading: false,
            products: [],
            store: {}
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