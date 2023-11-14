<template>
    <div>
        <div v-if="form.items.length" class="mb-4">
            <h5>Order Items:</h5>
            <div v-for="item in form.items">
                <li>
                        <strong>{{ getItemName(item.product_id) }}</strong>
                        <p class="quantity">{{  'Quantity: ' + item.count }}</p>
                </li>
            </div>
        </div>

        <div class="form-group">
            <label>Item</label>
            <select v-model="item.product_id" class="form-control">
                <option v-for="product in productOptions" :value="product.value">{{  product.label }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>No. of item</label>
            <Field type="number" class="form-control" name="count" v-model="item.count"/>
        </div>

        <button @click="addItem()" class="btn btn-primary mt-2">Add item</button>

        <div class="form-group mt-5">
            <label>Notes</label>
            <Field v-model="form.notes" class="form-control">
                <textarea v-model="form.notes" class="form-control"></textarea>
            </Field>
        </div>
    </div>
</template>

<script>
export default {
    name: 'OrderForm',

    computed: {
        totalAmount () {
            let amount = 0;

            this.form.items.forEach(item => {
                const product = this.products.find(product => product.id === item.product_id)

                amount = amount + (item.count * product.price)
            })

            return amount;
        },

        productOptions() {
            const products = this.products.filter(product => !this.form.items.some(item => item.product_id === product.id))
        
            return products.map(product => {
                return {
                    label: product.title,
                    value: product.id
                }
            })
        }
    },

    data () {
        return {
            form: {
                store_id: null,
                total_amount: null,
                items: [],
                notes: null,
                created_by: null
            },
            item: {
                product_id: null,
                count: null
            }
        }
    },

    methods: {
        addItem() {
            this.item.count = parseInt(this.item.count)
            this.form.items.push(this.item);

            this.item = {
                product_id: null,
                count: null
            }
        },

        getItemName(id) {
            return this.products.find(product => product.id === id).title
        }
    },

    props: {
        products: {
            default: [],
            type: Array
        }
    }
}
</script>

<style>
.quantity {
    margin-left: 1.25rem
}
</style>