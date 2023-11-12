<template>
    <div>
        <div class="spinner-border" role="status" v-if="loading"></div>
        <div v-else>
            <h2>{{ product.title }}</h2>
            <Form
                @submit:form="submitForm"
                :product="product"
                :mode="'edit'"
            />
        </div>
    </div>
</template>

<script>
import { useProductStore } from "@/stores/products";
import Form from "./Form.vue"
export default {
    name: 'ShowProduct',

    created() {
        this.prepareComponent()
    },

    components: {
        Form
    },

    data () {
        return {
            product: {},
            loading: false
        }
    },

    methods: {
        prepareComponent () {
            this.loading = true;

            const store = useProductStore()
            const productId = parseInt(this.$route.params.productId)

            store.getProductById(productId)
                .then((product) => {
                    this.product = product
                })
                .finally(() => {
                    this.loading = false;
                })
        }
    }
}
</script>