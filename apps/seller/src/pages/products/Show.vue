<template>
    <div class="m-4 w-100">
        <div class="spinner-border" role="status" v-if="loading"></div>
        <div v-else>
            <h2>Update: {{ product.title }}</h2>
            <div class="d-flex justify-content-between">
                <div>
                    <h3>Product Info</h3>
                    <Form
                        @submit:form="submitForm"
                        :product="product"
                        :mode="'edit'"
                    />
                </div>

                <div>
                    <h3>Product Images</h3>
                    <div>
                        <div v-for="image in product.images" class="product-image">
                            <img :src="getImagePath(image.url)"/>
                        </div>
                    </div>
                    <input type="file" ref="fileInput" @change="handleFileChange" />
                    <button class="btn btn-primary" @click="uploadImage">Upload</button>
                </div>
            </div>
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
        },

        uploadImage () {
            const file = this.$refs.fileInput.files[0];

            const data = {
                images: [
                    {
                        product_id: this.product.id,
                        file: file
                    }
                ]
            }

            const store = useProductStore()

            store.uploadImages(data)
        },

        getImagePath(url) {
            url = url.replace('instapay-minio', 'localhost');

            const imageUrl = url.split('?')[0];

            return imageUrl
        }
    }
}
</script>

<style lang="scss">
.c-product-form {
    margin: 1rem 3rem;

    .form-control {
        width: 30rem;
    }
}
.product-image {
    padding: 0.5rem;
    border: 1px solid black;
    width: fit-content;
    
    img {
        height: 8rem;
        width: 8rem;
    }
}
</style>