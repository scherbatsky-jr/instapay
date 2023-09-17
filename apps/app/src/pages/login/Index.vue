<template>
    <div>
        <h1>Login</h1>
        <Form @submit="onSubmit">
            <div>
                <Field
                    v-model="credentials.username"
                    name="email"
                    rules="required|email"
                    type="email"
                >
                </Field>
                <ErrorMessage name="email" />
            </div>
            <div>
                <Field
                    v-model="credentials.password"
                    name="password"
                    rules="required"
                    type="password"
                >
                </Field>
                <ErrorMessage name="email" />
            </div>
            <div>
                <button type="submit">Log In</button>
            </div>
        </Form>
    </div>
</template>

<script>
import { useAuthStore } from "@/stores/auth"

export default {
    name: "Login",

    data () {
        return {
            credentials: {
                username: null,
                password: null,
                withRoles: ["Admin"]
            }
        }
    },

    methods: {
        onSubmit() {
            const store = useAuthStore()

            store.login(this.credentials)
                .then((user) => {
                    if (user.id) {
                        this.$router.push('/dashboard')
                    }
                })
        }
    }
}
</script>