<template>
    <div class="d-flex flex-column w-100 m-auto">
        <h1 class="text-center mb-4">Register your Merchant Account</h1>
        <Form @submit="submitForm" v-slot="{ errors }" class="m-auto">
      <!-- First Name Field -->
      <div class="mb-3">
        <label for="firstName" class="form-label">First Name:</label>
        <Field
          v-model="form.firstName"
          name="firstName"
          id="firstName"
          rules="required"
          :class="{ 'is-invalid': errors.firstName }"
          class="form-control"
        />
        <div v-if="errors.firstName" class="invalid-feedback">{{ errors.firstName }}</div>
      </div>
  
      <!-- Last Name Field -->
      <div class="mb-3">
        <label for="lastName" class="form-label">Last Name:</label>
        <Field
          v-model="form.lastName"
          name="lastName"
          id="lastName"
          rules="required"
          :class="{ 'is-invalid': errors.lastName }"
          class="form-control"
        />
        <div v-if="errors.lastName" class="invalid-feedback">{{ errors.lastName }}</div>
      </div>
  
      <!-- Email Field -->
      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <Field
          type="email"
          v-model="form.email"
          name="email"
          id="email"
          rules="required|email"
          :class="{ 'is-invalid': errors.email }"
          class="form-control"
        />
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
      </div>
  
      <!-- Contact Field -->
      <div class="mb-3">
        <label for="contact" class="form-label">Contact:</label>
        <Field
          v-model="form.contact"
          name="contact"
          id="contact"
          rules="required|numeric"
          :class="{ 'is-invalid': errors.contact }"
          class="form-control"
        />
        <div v-if="errors.contact" class="invalid-feedback">{{ errors.contact }}</div>
      </div>
  
      <!-- Password Field -->
      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <Field
          type="password"
          v-model="form.password"
          name="password"
          id="password"
          rules="required|min:6"
          :class="{ 'is-invalid': errors.password }"
          class="form-control"
        />
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
      </div>
  
      <!-- Confirm Password Field -->
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password:</label>
        <Field
          type="password"
          v-model="form.confirmPassword"
          name="confirmPassword"
          id="confirmPassword"
          rules="required|confirmed:password"
          :class="{ 'is-invalid': errors.confirmPassword }"
          class="form-control"
        />
        <div v-if="errors.confirmPassword" class="invalid-feedback">{{ errors.confirmPassword }}</div>
      </div>
  
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary" :disabled="disableSubmit">Register</button>
    </Form>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from "@/stores/auth"
  export default {
    name: "Register",
    data() {
      return {
        form: {
          firstName: '',
          lastName: '',
          email: '',
          contact: '',
          password: '',
          confirmPassword: '',
        },
        disableSubmit: false
      };
    },
    methods: {
      submitForm() {
        const registerInput = {
            username: this.form.email,
            password: this.form.password,
            withRoles: ['Seller'],
            profile: {
                given_name: this.form.firstName,
                surname: this.form.lastName,
                contact: this.form.contact,
                status: 0
            }
        }

        const store = useAuthStore()

        this.disableSubmit = true

        store.signUp(registerInput)
            .then((user) => {
                console.log(user)
                if (user.id) {
                    this.$router.push({name: 'payment-plans' })
                }
            })
            .finally(() => {
              this.disableSubmit = false
            })
 
      },
    },
  };
  </script>
  
  <style>
  .form-control {
    width: 25rem;
  }
  </style>
  