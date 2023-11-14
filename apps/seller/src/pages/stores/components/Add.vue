<template>
    <div class="d-flex flex-column m-4 w-100">
        <h1>Add a store</h1>
        <Form
            @cancel="onCancel()"
            @form:submit="createStore"
        />
    </div>
</template>

<script>
import Form from "./Form.vue";
import { useStoreStore } from "../../../stores/stores";

export default {
    name: "AddStore",

    components: {
        Form
    },

    methods: {
        createStore(storeData) {
            console.log(storeData)
            const store = useStoreStore();

            store.createStore(storeData);
        },

        onCancel () {
            this.$router.push({ name: 'dashboard' })
        }
    },
}
</script>

<style lang="scss">
.c-store-form {
    .form-control {
        width: 50%
    }
}
</style>
