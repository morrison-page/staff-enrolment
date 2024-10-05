<template>
  <div>
    <h1>{{ message }}</h1>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const message = ref('');

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000'); // PHP backend URL
    message.value = response.data.message;
  } catch (error) {
    console.error('Error fetching message:', error);
  }
});
</script>

<style scoped>
h1 {
  color: #42b983;
}
</style>
