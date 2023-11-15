<template>
    <div class="d-flex flex-column m-4 w-100">
        <h1>Add a store</h1>
        <Form
            @cancel="onCancel()"
            @form:submit="createStore"
            :disable-submit="disableSubmit"
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

    data () {
        return {
            disableSubmit: false
        }
    },

    methods: {
        createStore(storeData) {
            const store = useStoreStore();

            this.disableSubmit = true
            store.createStore(storeData)
                .then((store) => {
                    this.$router.push('/dashboard')
                })
                .finally(() => {
                    this.disableSubmit = false
                });
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
