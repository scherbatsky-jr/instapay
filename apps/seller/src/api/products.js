import { createProductMutation, product, products, uploadImagesMutation  } from "../graphql/products";

import client from '@/apolloClient'

const createProduct = (data) => {
   return client
    .mutate({
        mutation: createProductMutation,
        variables: data
    })
    .then((response) => {
        return response.data.product
    })
    .catch((error) => {
        throw error;
    })
}

const getProductById = (id) => {
    return client
    .query({
      query: product,
      variables: {
        id: id
      }
    })
    .then((response) => {
        return response.data.product
    })
    .catch((error) => {
        throw error
    })
}

const fetchProducts = (filters, sort) => {
    return client.query({
        query: products,
        variables: {
            filters: filters,
            sort: sort
        }
    })
    .then((response) => {
        return response.data.products
    })
    .catch((error) => {
        throw error;
    })
}

const uploadImages = (data) => {
    return client
     .mutate({
         mutation: uploadImagesMutation,
         variables: data
     })
     .then((response) => {
         return response.data.images
     })
     .catch((error) => {
         throw error;
     })
    }
export {
    createProduct,
    getProductById,
    fetchProducts,
    uploadImages
}