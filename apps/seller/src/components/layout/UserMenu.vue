<template>
    <BNavbar toggleable="lg" type="dark" variant="primary">
        <div class="d-flex justify-content-between w-100">
            <BNavbarBrand href="/dashboard" class="text-white">InstaPay Merchant</BNavbarBrand>
            <BNavbarNav class="d-flex text-white"  v-if="isLoggedIn">
                <BNavItem ><Icon icon="mingcute:user-4-line" class="icon" /></BNavItem>
                <BNavItem @click="onLogout"><Icon icon="icon-park-outline:logout" class="icon"/></BNavItem>
            </BNavbarNav>
        </div>
    </BNavbar>
</template>

<script>
import { useAuthStore } from "@/stores/auth"

export default {
    name: "UserMenu",

    computed: {
        isLoggedIn() {
            const store = useAuthStore()

            return store.isLoggedIn
        }
    },

    methods: {
        onLogout() {
            const store = useAuthStore()

            store.logout()
        }
    }
}
</script>

<style lang="scss">
.icon {
    height: 2rem;
    width: 2rem;
    color: white;
}
</style>
