<template>
    <BModal v-model="modal" hideHeader hideFooter>
        <h3>Create an Order</h3>
        <OrderForm />
    </BModal>
</template>

<script>
import OrderForm from "./OrderForm.vue"

export default {
    name: 'OrderPopup',

    components: {
        OrderForm
    },

    data () {
        return {
            modal: true
        }
    }
}
</script>
