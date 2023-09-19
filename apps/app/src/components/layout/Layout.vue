<template>
    <div>
        <UserMenu />
        <Menu v-if="isLoggedIn" />
        <router-view></router-view>
    </div>
</template>

<script>
import Menu from './Menu.vue'
import UserMenu from './UserMenu.vue'

import { useAuthStore } from "@/stores/auth"

export default {
    name: "Layout",

    components: { 
        Menu,
        UserMenu
    },

    computed: {
        isLoggedIn () {
            const store = useAuthStore()

            return store.isLoggedIn
        }
    }
}
</script>
