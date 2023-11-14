import gql from "graphql-tag";

export const createProductMutation = gql`
    mutation createProduct(
        $title: String!
        $description: String!
        $price: Float!
        $stock: Int!
        $brand: String
        $store_id: Int!
        $created_by: Int!
    ) {
        product: createProduct(
            title: $title
            description: $description
            price: $price
            stock: $stock
            brand: $brand
            store_id: $store_id
            created_by: $created_by
        ) {
            title
            description
            price
            id
            stock
            brand
            store_id
        }
    }
`

export const product = gql`
    query product (
        $id: Int!
    ) {
        product (id: $id) {
            title
            description
            price
            id
            stock
            brand
            store_id
            images {
                id
                url
            }
        }
    }
`

export const products = gql`
    query products (
        $filters: FilterInput
        $sort: SortInput
    ) {
        products (
            filters: $filters
            sort: $sort
        ) {
            title
            description
            price
            id
            stock
            brand
            store_id
        }
    }
`

export const uploadImagesMutation = gql`
    mutation uploadImages (
        $images: [ImageInput]
    ) {
        uploadImages(
            images: $images
        ) {
            id
            product_id
            url
        }
    }
`
