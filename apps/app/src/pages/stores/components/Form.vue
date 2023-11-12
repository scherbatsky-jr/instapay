<template>
    <Form @submit="submitForm" v-slot="{ errors }">
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
  
      <!-- Instagram Field -->
      <div class="mb-3">
        <label for="instagram" class="form-label">Instagram:</label>
        <Field
          v-model="form.instagram"
          name="instagram"
          id="instagram"
          rules="required"
          :class="{ 'is-invalid': errors.instagram }"
          class="form-control"
        />
        <div v-if="errors.instagram" class="invalid-feedback">{{ errors.instagram }}</div>
      </div>
  
      <!-- Phone Field -->
      <div class="mb-3">
        <label for="phone" class="form-label">Phone:</label>
        <Field
          v-model="form.phone"
          name="phone"
          id="phone"
          rules="required"
          :class="{ 'is-invalid': errors.phone }"
          class="form-control"
        />
        <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone }}</div>
      </div>
  
      <!-- Name Field -->
      <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <Field
          v-model="form.name"
          name="name"
          id="name"
          rules="required"
          :class="{ 'is-invalid': errors.name }"
          class="form-control"
        />
        <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
      </div>
  
      <!-- TikTok Field -->
      <div class="mb-3">
        <label for="tiktok" class="form-label">TikTok:</label>
        <Field
          v-model="form.tiktok"
          name="tiktok"
          id="tiktok"
          rules="required"
          :class="{ 'is-invalid': errors.tiktok }"
          class="form-control"
        />
        <div v-if="errors.tiktok" class="invalid-feedback">{{ errors.tiktok }}</div>
      </div>
  
      <!-- Website Field -->
      <div class="mb-3">
        <label for="website" class="form-label">Website:</label>
        <Field
          v-model="form.website"
          name="website"
          id="website"
          :class="{ 'is-invalid': errors.website }"
          class="form-control"
        />
      </div>
  
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
    </Form>
  </template>
  
  <script>
  export default {
    name: 'StoreForm',
    data() {
      return {
        form: {
          email: '',
          instagram: '',
          phone: '',
          name: '',
          tiktok: '',
          website: '',
          user_id: null
        }
      };
    },
    methods: {
      async submitForm() {
        console.log('I am here')
        this.$emit('form:submit', this.form)
      }
    }
  };
  </script>
  