<template>
  <div class="container">
    <header class="header">
      <h1>STOCK CENTER</h1>
      <button class="btn btn-primary" @click="openModal">New item</button>
    </header>

    <div class="items-list">
      <h2>Items in stock</h2>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Price, USD</th>
            <th>Date and time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="item.id">
            <td>{{ index + 1 }}</td>
            <td>{{ item.name }}</td>
            <td>{{ formatPrice(item.price) }}</td>
            <td>{{ formatDateTime(item.dateTime) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <ItemModal 
      v-if="showModal" 
      @close="closeModal" 
      @item-added="fetchItems" 
    />
  </div>
</template>

<script>
import ItemModal from './components/ItemModal.vue';
import axios from 'axios';

export default {
  name: 'App',
  components: {
    ItemModal
  },
  data() {
    return {
      items: [],
      showModal: false
    };
  },
  mounted() {
    this.fetchItems();
  },
  methods: {
    openModal() {
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    async fetchItems() {
      try {
        const response = await axios.get('http://localhost:8000/api/items');
        this.items = response.data;
        console.log('Fetched items:', this.items);
      } catch (error) {
        console.error('Error fetching items:', error);
      }
    },
    formatPrice(price) {
      return parseFloat(price).toFixed(2);
    },
    formatDateTime(dateTime) {
      if (!dateTime) return '';
      
      const date = new Date(dateTime);
      
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      const seconds = String(date.getSeconds()).padStart(2, '0');
      
      return `${day}.${month}.${year} ${hours}:${minutes}:${seconds}`;
    }
  }
};
</script>
