<template>
    <div class="d-flex flex-column m-4">
        <h1>Add a store</h1>
        <Form
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
        }
    }
}
</script>
