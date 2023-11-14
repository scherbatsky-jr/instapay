<template>
    <BNavbar oggleable="lg" type="dark" variant="primary">
        <BNavbarBrand href="/dashboard" class="text-white">InstaPay</BNavbarBrand>
        <BNavbarToggle target="nav-collapse" />
        <BCollapse id="nav-collapse" is-nav >
            <BNavbarNav class="d-flex" v-if="isLoggedIn">
                <BNavItem class="text-white" >Profile</BNavItem>
                <BNavItem @click="onLogout" class="text-white">Logout</BNavItem>
            </BNavbarNav>
        </BCollapse>
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

            this.$router.push({name: 'login'})
        }
    }
}
</script>
