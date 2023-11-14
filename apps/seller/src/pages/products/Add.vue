<template>
    <div class="d-flex flex-column m-4 p-add-product">
        <h2>Add a product</h2>
        <Form
            @form:submit="addProduct"
        />
    </div>
</template>

<script>
import Form from './Form.vue';

import { useProductStore } from '@/stores/products';
import { useAuthStore } from "@/stores/auth";

export default {
    name: 'AddProduct',

    components: {
        Form
    },

    methods: {
        addProduct(product) {
            const store = useProductStore();
            const authStore = useAuthStore();

            product.created_by = authStore.getUser.id

            store.createProduct(product)
                .then((product) => {
                    if (product.id) {
                        this.$router.push({name: 'show-store', params: { storeId: product.store_id }})
                    }
                })
        }
    }
}
</script>

<style lang="scss">
.p-add-product {
    width: 100%
}
</style>