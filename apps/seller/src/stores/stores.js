import { createStore, getStoreById, fetchStores, getStoreStats } from "../api/stores";
import { defineStore } from "pinia";

export const useStoreStore = defineStore('stores', {
    actions: {
        createStore(data) {
            return createStore(data).then((store) => {
                // this.stores.push(store)
                return store
            })
        },

        fetchStores(filters, sort) {
           return fetchStores(filters, sort).then(stores => {
                this.stores = stores;
                return stores;
           })
        },

        getStoreById(id) {
            return getStoreById(id).then((store) => {
                return store;
            })
        },

        getStoreStats(id) {
            return getStoreStats(id).then((stats) => {
                return stats;
            })
        }
    },

    getters: {
        getStores(state) {
            return state.stores;
        }
    },

    state: () => {
        stores: []
    }
})