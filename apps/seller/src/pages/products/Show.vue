<template>
    <div class="m-4 w-100">
        <div class="spinner-border" role="status" v-if="loading"></div>
        <div v-else>
            <h2>Update: {{ product.title }}</h2>
            <div class="content">
                <div>
                    <h3>Product Info</h3>
                    <Form
                        @form:submit="submitForm"
                        :product="product"
                        :mode="'edit'"
                        :disableSubmit="disableSubmit"
                    />
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-4">
                        <h3>Product Images</h3>
                        <button
                            class="btn btn-danger"
                            v-if="selectedImages.length"
                            @click="deleteImages"
                            :disabled="disableSubmit"
                            >
                        Delete Selected Images</button>
                    </div>
                    <div class="images">
                        <div v-for="image in product.images" class="product-image">
                            <input type="checkbox" @change="onCheck(image.id)" class="image-check"/>
                            <img :src="getImagePath(image.url)"/>
                        </div>
                    </div>
                    <h5 class="mt-5 mb-2">Upload a new image</h5>
                    <input type="file" ref="fileInput" @change="handleFileChange" />
                    <button class="btn btn-primary" @click="uploadImage" :disabled="disableSubmit">Upload</button>
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
            loading: false,
            disableSubmit: false,
            selectedImages: []
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

        onCheck (id) {
            const index = this.selectedImages.indexOf(id);

            if (index !== -1) {
                this.selectedImages.splice(index, 1);
            } else {
                this.selectedImages.push(id);
            }

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

            this.disableSubmit = true

            store.uploadImages(data)
                .then((images) => {
                    window.location.reload()
                })
                .finally(() => {
                    this.disableSubmit = false
                })
        },

        deleteImages() {
            const store = useProductStore()

            this.disableSubmit = true

            store.deleteImages(this.selectedImages)
                .then(response => {
                    window.location.reload()
                })
        },

        getImagePath(url) {
            url = url.replace('instapay-minio', 'localhost');

            const imageUrl = url.split('?')[0];

            return imageUrl
        },

        submitForm(data) {
            data.id = this.product.id
            delete data.store_id

            const store = useProductStore()

            this.disableSubmit = true

            store.updateProduct(data)
                .then(product => {
                    this.$router.push({ name: 'show-store' , params: { storeId: this.product.store_id }})
                })
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

.content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 5rem;
}

.images {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-gap: 1rem;
}

.image-check {
    background-color: white;
    position: absolute;
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