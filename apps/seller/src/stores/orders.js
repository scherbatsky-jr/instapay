import { createOrder, getOrderById, fetchOrders } from "@/api/orders";
import { defineStore } from "pinia";

export const useOrderStore = defineStore('orders', {
    actions: {
        createOrder(data) {
            return createOrder(data).then((order) => {
                return order
            })
        },

        fetchOrders(filters, sort) {
           return fetchOrders(filters, sort).then(orders => {
                return orders;
           })
        },

        getOrderById(id) {
            return getOrderById(id).then((order) => {
                return order;
            })
        }
    },
})