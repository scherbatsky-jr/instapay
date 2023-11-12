<template>
    <h1>Your Stores</h1>
    <ul v-if="stores.length">
        <li v-for="store in stores">
            <router-link :to="{ name: 'show-store', params: {storeId: store.id}}">{{ store.name }}</router-link>
        </li>
    </ul>
</template>

<script>
import { useStoreStore } from '../../../stores/stores';

export default {
    name: "Stores",

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