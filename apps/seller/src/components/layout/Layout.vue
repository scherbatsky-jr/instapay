<template>
    <div class="c-layout">
        <UserMenu />
        <div class="c-layout__body bg-light">
            <!-- <Menu v-if="isLoggedIn" /> -->
            <router-view></router-view>
        </div>
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

<style scoped lang="scss">
.c-layout {
    &__body {
        display: flex;
        min-height: 98vh;
    }
}
</style>
