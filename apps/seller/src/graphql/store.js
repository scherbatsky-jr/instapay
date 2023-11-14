import gql from "graphql-tag";

export const createStoreMutation = gql`
    mutation createStore(
        $email: String
        $instagram: String
        $phone: String
        $name: String!
        $tiktok: String
        $website: String
    ) {
        createStore(
            email: $email
            instagram: $instagram
            phone: $phone
            name: $name
            tiktok: $tiktok
            website: $website
        ) {
            id
            email
            instagram
            phone
            name
            tiktok
            website
            user_id
        }
    }
`

export const store = gql`
    query store (
        $id: Int!
    ) {
        store (id: $id) {
            id
            email
            instagram
            phone
            name
            tiktok
            website
            user_id
        }
    }
`

export const stores = gql`
    query stores(
        $filters: FilterInput
        $sort: SortInput
    ) {
        stores (
            filters: $filters
            sort: $sort
        ) {
            id
            email
            instagram
            phone
            name
            tiktok
            website
            user_id
        }
    }
`

export const storeStats = gql`
    query storeStats ($id: Int!) {
        storeStats (id: $id) {
            total_orders
            open
            payment_pending
            payment_success
            payment_failed
            shipped
            delivered
        }
    }
`
