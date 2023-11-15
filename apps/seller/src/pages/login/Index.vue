<template>
    <div class="p-login container" >
        <h1>Login</h1>
        <Form @submit="onSubmit" class="p-login__form">
                <spam class="error-message" v-if="errorMessage">{{  errorMessage }}</spam>
                <BFormGroup label="Email" class="w-20">
                    <Field
                        v-model="credentials.username"
                        name="email"
                        rules="required|email"
                        class="form-control"
                    >
                        <BFormInput v-model="credentials.username" type="email"/>
                    </Field>
                    <ErrorMessage name="email" class="error-message"/>
                </BFormGroup>
                <BFormGroup label="Password">
                    <Field
                        v-model="credentials.password"
                        name="password"
                        rules="required"
                    >
                    <BFormInput v-model="credentials.password" type="password"/>
                    </Field>
                    <ErrorMessage name="password" class="error-message"/>
                </BFormGroup>
                <BFormGroup>
                    <BButton class="mt-4" variant="primary" type="submit" :disabled="disableSubmit">Log In</BButton>
                </BFormGroup>
        </Form>

        <router-link :to="{name: 'register'}">Click here to register!</router-link>
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
                withRoles: ["Seller"]
            },
            disableSubmit: false,
            errorMessage: null
        }
    },

    methods: {
        onSubmit() {
            const store = useAuthStore()

            this.disableSubmit = true
            this.errorMessage = null

            store.login(this.credentials)
                .then((user) => {
                    if (user.id) {
                        this.$router.push('/dashboard')
                    }
                })
                .catch(() => {
                    this.errorMessage = "Invalid Credentials"
                })
                .finally(() => {
                    this.disableSubmit = false
                })
        }
    }
}
</script>

<style scoped lang="scss">
.p-login {
    text-align: center;
    padding: 4rem;

    &__form {
        margin: auto;
        padding: 1rem;
        text-align: left;
        width: fit-content;

        fieldset {
            min-width: 20rem !important;
        }
    }
}
</style>