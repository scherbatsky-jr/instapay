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
    query stores {
        stores {
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
