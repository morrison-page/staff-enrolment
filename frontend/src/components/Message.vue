<template>
  <div>
    <h1>User IDs</h1>
    <ul v-if="users.length">
      <li v-for="user in users" :key="user.user_id">{{ user.user_id }}</li>
    </ul>
    <p v-else>No users found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);

const fetchUsers = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:3030/apis/users');
    console.log('Response data:', response.data); // Log the response data
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

onMounted(() => {
  fetchUsers();
});
</script>

<style scoped>
/* Add your styles here */
</style>