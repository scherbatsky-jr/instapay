import client from "@/apolloClient";

import { createOrderMutation, orders, order } from "@/graphql/orders";

const createOrder = (data) => {
    return client
     .mutate({
         mutation: createOrderMutation,
         variables: data
     })
     .then((response) => {
         return response.data
     })
     .catch((error) => {
         throw error;
     })
}

const getOrderById = (id) => {
    return client
    .query({
      query: order,
      variables: {
        id: id
      }
    })
    .then((response) => {
        return response.data.order
    })
    .catch((error) => {
        throw error
    })
}

const fetchOrders = (filters, sort) => {
    return client.query({
        query: orders,
        variables: {
            filters: filters,
            sort: sort
        }
    })
    .then((response) => {
        return response.data.orders
    })
    .catch((error) => {
        throw error;
    })
}

export {
    createOrder,
    getOrderById,
    fetchOrders
}
