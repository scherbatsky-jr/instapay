import { createStore, getStoreById, fetchStores } from "../api/stores";
import { defineStore } from "pinia";

export const useStoreStore = defineStore('stores', {
    actions: {
        createStore(data) {
            return createStore(data).then((store) => {
                this.stores.push(store)
                return store
            })
        },

        fetchStores() {
           return fetchStores().then(stores => {
                this.stores = stores;
                return stores;
           })
        },

        getStoreById(id) {
            return getStoreById(id).then((store) => {
                return store;
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