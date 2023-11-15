<template>
    <div class="border-spinner"></div>
</template>

<script>
import { useAuthStore } from "@/stores/auth"

export default {
    name: 'PlanSuccess',

    created () {
        this.prepareComponent()
    },

    methods: {
        prepareComponent() {
            const auth = useAuthStore()

            const user = auth.getUser

            const plan = parseInt(this.$route.params.plan)

            let plan_end_date = new Date()
            console.log(plan_end_date)
            if (plan == 1) {
                plan_end_date.setMonth(plan_end_date.getMonth() + 1)
            } else if (plan == 2) {
                plan_end_date.setMonth(plan_end_date.getMonth() + 6)
            } else if (plan == 3) {
                plan_end_date.setMonth(plan_end_date.getMonth() + 12)
            }

            plan_end_date = plan_end_date.toISOString().replace(/T/, ' ').replace(/\..+/, '');

            if (user.profile.status == 1) {
                this.$router.push({ name: 'dashboard' })
            } else {
                const userData = {
                    id: user.id,
                    profile: {
                        given_name: user.profile.given_name,
                        surname: user.profile.surname,
                        status: 1,
                        contact: user.profile.contact,
                        plan: plan,
                        plan_end_date: plan_end_date
                    }
                }

                auth.updateProfile(userData)
                    .then((user) => {
                        if (user.profile.status == 1) {
                            this.$router.push({ name: 'dashboard' })
                        }
                    })
            }
        }
    }
}
</script>