<template>
    <BNavbar toggleable="lg" type="dark" variant="primary">
        <div class="d-flex justify-content-between w-100">
            <BNavbarBrand href="/dashboard" class="text-white">InstaPay Merchant</BNavbarBrand>
            <BNavbarNav class="d-flex text-white"  v-if="isLoggedIn">
                <BNavItem class="text-white" >Profile</BNavItem>
                <BNavItem @click="onLogout">Logout</BNavItem>
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
