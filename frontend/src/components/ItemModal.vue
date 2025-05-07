<template>
  <div class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>New item</h2>
        <button class="modal-close" @click="close">&times;</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="title">Title</label>
            <input
              type="text"
              id="title"
              v-model="form.name"
              class="form-control"
              :class="{ 'is-invalid': errors.name }"
            />
            <div v-if="errors.name" class="invalid-feedback">
              {{ errors.name }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="price">Price</label>
            <input
              type="text"
              id="price"
              v-model="form.price"
              class="form-control"
              :class="{ 'is-invalid': errors.price }"
            />
            <div v-if="errors.price" class="invalid-feedback">
              {{ errors.price }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="dateTime">Date and time</label>
            <input
              type="text"
              id="dateTime"
              v-model="form.dateTime"
              class="form-control"
              placeholder="dd.mm.yyyy hh:mm:ss"
              :class="{ 'is-invalid': errors.dateTime }"
            />
            <div v-if="errors.dateTime" class="invalid-feedback">
              {{ errors.dateTime }}
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" @click="close">Close</button>
        <button class="btn btn-primary" @click="submitForm">Add</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ItemModal',
  data() {
    return {
      form: {
        name: '',
        price: '',
        dateTime: ''
      },
      errors: {}
    };
  },
  methods: {
    close() {
      this.$emit('close');
    },
    resetForm() {
      this.form = {
        name: '',
        price: '',
        dateTime: ''
      };
      this.errors = {};
    },
    async submitForm() {
      try {
        console.log('Submitting form with data:', {
          name: this.form.name,
          price: this.form.price,
          dateTime: this.form.dateTime
        });
        
        const response = await axios.post('http://localhost:8000/api/items', {
          name: this.form.name,
          price: this.form.price,
          dateTime: this.form.dateTime
        });
        
        console.log('Response:', response.data);
        
        if (response.data.success) {
          this.resetForm();
          this.$emit('item-added');
          this.close();
        }
      } catch (error) {
        console.error('Error submitting form:', error);
        
        if (error.response && error.response.data && error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.log('Validation errors:', this.errors);
        } else {
          // Generic error handling
          this.errors = { general: 'An error occurred while submitting the form. Please try again.' };
        }
      }
    }
  }
};
</script>
