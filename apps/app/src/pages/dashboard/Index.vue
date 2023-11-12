<template>
    <h1>Welcome to dashboard</h1>
    <p>Here are your stores</p>
    <p v-for="store in stores">{{ store.name }}</p>
</template>

<script>
import { useStoreStore } from '../../stores/stores';

export default {
    name: "Dashboard",

    created() {
        const store = useStoreStore();

        store.fetchStores().then(() => {
            this.stores = store.getStores;
        });
    },

    data () {
        return {
            stores: []
        }
    }
}
</script>
