import { createProduct, getProductById, fetchProducts, uploadImages } from "@/api/products";
import { defineStore } from "pinia";

export const useProductStore = defineStore('products', {
    actions: {
        createProduct(data) {
            return createProduct(data).then((product) => {
                return product
            })
        },

        fetchProducts(filters, sort) {
           return fetchProducts(filters, sort).then(products => {
                return products;
           })
        },

        getProductById(id) {
            return getProductById(id).then((product) => {
                return product;
            })
        },

        uploadImages(data) {
            return uploadImages(data).then((images) => {
                return images;
            })
        }
    },
})