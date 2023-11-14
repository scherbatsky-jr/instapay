<template>
    <div class="c-store-stat">
        <h2>Overview</h2>
        <div class="spinner-border" v-if="loading"></div>
        <div v-else class="stats">
            <div class="card flex-row">
                <Icon icon="fa6-solid:users" />
                <div>
                    <p>Total Orders</p>
                    <h3>{{ stats.total_orders }}</h3>
                </div>
            </div>
            <div class="card flex-row">
                <Icon icon="lets-icons:box-open" />
                <div>
                    <p>Open</p>
                    <h3>{{ stats.open }}</h3>
                </div>
            </div>
            <div class="card flex-row">
                <Icon icon="material-symbols:pending-outline" />
                <div>
                    <p>Payment Pending</p>
                    <h3>{{ stats.payment_pending }}</h3>
                </div>
            </div>
            <div class="card flex-row">
                <Icon icon="mdi:tick-circle" />
                <div><p>Payment Success</p>
                <h3>{{ stats.payment_success }}</h3></div>
            </div>
            <div class="card flex-row">
                <Icon icon="maki:cross" />
                <div><p>Payment Failed</p>
                <h3>{{ stats.payment_failed }}</h3></div>
            </div>
            <div class="card flex-row">
                <Icon icon="streamline:transfer-van-solid" />
                <div><p>Shipped</p>
                <h3>{{ stats.shipped }}</h3></div>
            </div>
            <div class="card flex-row">
                <Icon icon="mdi:package-variant-closed-delivered" />
                <div><p>Delivered</p>
                <h3>{{ stats.delivered }}</h3></div>
            </div>
        </div>
    </div>
</template>

<script>
import { useStoreStore } from '@/stores/stores';
export default {
    name: "StoreStat",

    props: {
        storeId: {
            required: true,
            type: Number
        }
    },

    created() {
        this.prepareComponent()
    },

    data () {
        return {
            stats: {},
            loading: false
        }
    },

    methods: {
        prepareComponent() {
            this.loading = true
            const store = useStoreStore()

            store.getStoreStats(this.storeId)
                .then((stats) => {
                    this.stats = stats
                })
                .finally(() => {
                    this.loading = false;
                })
        }
    }
}
</script>

<style lang="scss">
.stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 1rem;

    .card {
        justify-content: space-between;

        div {
            display: flex;
            flex-direction: column;
            align-items: end;
        }
        svg {
            width: 5rem;
            height: 5rem;
            padding: 0.5rem;
        }
    }
}
</style>