import { createStoreMutation, store, stores } from "../graphql/store";

import client from '@/apolloClient'

const createStore = (data) => {
   return client
    .mutate({
        mutation: createStoreMutation,
        variables: data
    })
    .then((response) => {
        return response.data
    })
    .catch((error) => {
        throw error;
    })
}

const getStoreById = (id) => {
    return client
    .query({
      query: store,
      variables: {
        id: id
      }
    })
    .then((response) => {
        return response.data.store
    })
    .catch((error) => {
        throw error
    })
}

const fetchStores = () => {
    return client.query({
        query: stores
    })
    .then((response) => {
        return response.data.stores
    })
    .catch((error) => {
        throw error;
    })
}

export {
    createStore,
    getStoreById,
    fetchStores
}