<template>
    <div>
        <div class="d-flex flex-column m-3">
            <h3>Your Stores</h3>
            <div class="spinner-border" v-if="loading"></div>
            <div class="stores m-4" v-else>
                <div class="card" v-for="store in stores" @click="onClickStore(store.id)">
                    <Icon icon="healthicons:market-stall-outline" class="icon"/>
                    <h5>{{  store.name }}</h5>
                </div>
                <div class="card" @click="onCreateStore()">
                    <Icon icon="zondicons:add-solid" class="icon"/>
                    <h5>Create a store</h5>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useStoreStore } from '../../stores/stores';
import { useAuthStore } from "@/stores/auth"

export default {
    name: "Dashboard",

    created() {
        this.prepareComponent()
    },

    data () {
        return {
            stores: [],
            loading: false
        }
    },

    methods: {
        prepareComponent() {
            this.loading = true

            const store = useStoreStore();
            const authStore = useAuthStore();

            store.fetchStores({
                key: 'user_id',
                value: authStore.getUser.id
            }).then(() => {
                this.stores = store.getStores;
            }).finally(() => {
                this.loading = false
            });

        },

        onClickStore(id) {
            this.$router.push({ name: 'show-store', params: { storeId: id}})
        },

        onCreateStore() {
            this.$router.push({ name: 'add-store' })
        }
    }
}
</script>

<style lang="scss">
.stores {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-gap: 2rem;
    
    .card {
        cursor: pointer;
        height: 12rem;
        width: 12rem;
        text-align: center;

        .icon {
            font-size: 5rem;
            margin: auto;
        }
    }
}
</style>
