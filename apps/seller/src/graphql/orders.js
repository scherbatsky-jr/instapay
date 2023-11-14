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

export const updateOrderMutation = gql`
    mutation updateOrder(
        $id: Int!
        $status: Int
        $addressInfo: AddressInput
        $tracking_number: String
    ) {
        order: updateOrder(
            id: $id
            status: $status
            addressInfo: $addressInfo
            tracking_number: $tracking_number
        ) {
            id
            store_id
            items {
                product_id
                product {
                    title
                    description
                    price
                }
                count
            }
            deliveryAddress {
                first_name
                last_name
                contact
                email
                street
                area
                city
                state
                landmarks
            }
            user_id
            total_amount
            notes
            status
            tracking_number
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
            deliveryAddress {
                contact
            }
            store_id
            items {
                id
                product {
                    id
                    title
                }
                count
            }
            status
            total_amount
            user_id
            created_by
            created_at
            tracking_number
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
            tracking_number
        }
    }
`