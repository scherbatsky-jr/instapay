<template>
    <Form @submit="submitForm" v-slot="{ errors }" class="c-product-form" >
      <!-- Title Field -->
      <div class="mb-3">
        <label for="title" class="form-label">Title:</label>
        <Field
          v-model="form.title"
          name="title"
          id="title"
          type="text"
          rules="required"
          :class="{ 'is-invalid': errors.title }"
          class="form-control"
        />
        <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
      </div>
  
      <!-- Description Field -->
      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <Field
          v-model="form.description"
          name="description"
          id="description"
          rules="required"
          :class="{ 'is-invalid': errors.description }"
          class="form-control"
        >
            <textarea v-model="form.description" class="form-control">
            </textarea>
        </Field>
        <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
      </div>
  
      <!-- Price Field -->
      <div class="mb-3">
        <label for="price" class="form-label">Price:</label>
        <Field
          v-model="form.price"
          name="price"
          id="price"
          type="number"
          rules="required|numeric"
          :class="{ 'is-invalid': errors.price }"
          class="form-control"
        />
        <div v-if="errors.price" class="invalid-feedback">{{ errors.price }}</div>
      </div>
  
      <!-- Stock Field -->
      <div class="mb-3">
        <label for="stock" class="form-label">Stock:</label>
        <Field
          v-model="form.stock"
          name="stock"
          id="stock"
          type="number"
          rules="required|numeric"
          :class="{ 'is-invalid': errors.stock }"
          class="form-control"
        />
        <div v-if="errors.stock" class="invalid-feedback">{{ errors.stock }}</div>
      </div>
  
      <!-- Brand Field -->
      <div class="mb-3">
        <label for="brand" class="form-label">Brand:</label>
        <Field
          v-model="form.brand"
          name="brand"
          id="brand"
          type="text"
          :class="{ 'is-invalid': errors.brand }"
          class="form-control"
        />
        <div v-if="errors.brand" class="invalid-feedback">{{ errors.brand }}</div>
      </div>
  
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
    </Form>
  </template>
  
  <script>
  export default {
    name: 'AddProduct',

    created () {
        this.prepareComponent();
    },

    data() {
      return {
        form: {
          title: '',
          description: '',
          price: '',
          stock: '',
          brand: '',
          store_id: '',
        },
      };
    },
    methods: {
      prepareComponent() {
        const storeId = parseInt(this.$route.params.storeId)

        this.form.store_id = storeId

        if (this.mode === 'edit') {
            this.form = this.product
        }
      },

      submitForm() {
        this.form.price = parseFloat(this.form.price)
        this.form.stock = parseInt(this.form.stock)
        this.$emit('form:submit', this.form)
      },
    },

    props: {
        mode: {
            default: 'add',
            type: String
        },
        product: {
            required: false,
            default: {},
            type: Object
        }

    }
  };
  </script>

  <style lang="scss">
  .c-product-form {
    margin: 0 5rem;
    .form-control {
      width: 75%;
    }
  }
  </style>
  