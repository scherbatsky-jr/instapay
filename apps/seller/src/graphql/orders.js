import gql from "graphql-tag";

export const createOrderMutation = gql`
    mutation createOrder(
        $store_id: Int!
        $created_by: Int!
        $total_amount: Float!
        $notes: String
        $items: [OrderItemInput!]!
    ) {
        order: createOrder(
            store_id: $store_id
            created_by: $created_by
            total_amount: $total_amount
            notes: $notes
            items: $items
        ) {
            id
            store_id
            items {
                product_id
                count
            }
            user_id
            notes
            status
            created_by
        }
    }
`

export const orders = gql`
    query orders(
        $filters: FilterInput
        $sort: SortInput
    ) {
        orders(
            filters: $filters
            sort: $sort
        ) {
            id
            store_id
            items {
                id
                product_id
                count
            }
            status
            total_amount
            user_id
            created_by
        }
    }
`

export const order = gql`
    query order(
       $id: Int!
    ) {
        order (
            id: $id
        ) {
            id
            store_id
            items
            status
            user_id
            created_by
        }
    }
`