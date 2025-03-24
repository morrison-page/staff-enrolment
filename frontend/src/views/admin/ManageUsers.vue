<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

interface User {
  user_id: string;
  email: string;
  first_name: string;
  last_name: string;
  job_title: string;
  access_level: string;
  login_attempts: number;
  last_login_attempt: string;
}

const store = useStore();
const router = useRouter();
const users = computed<User[]>(() => store.state.users.users);
const searchQuery = ref<string>('');

// Admin Access Level Check
const access_level: string = store.getters['auth/user'].access_level;
if (access_level !== 'admin') {
  router.push({ name: 'NotFound' });
}

// Vuex Actions
const fetchUsers = async (): Promise<void> => {
  await store.dispatch('users/fetchUsers');
};

const deleteUser = async (userId: string): Promise<void> => {
  await store.dispatch('users/deleteUser', userId);
  fetchUsers(); // Refresh
};

// Edit Actions
const editUser = (userId: string): void => {
  router.push({ path: `/manage/users/form/${userId}` });
};
const addUser = (): void => {
  router.push('/manage/users/form');
};

const filteredUsers = computed<User[]>(() => {
  if (!searchQuery.value) {
    return users.value;
  }
  const query = searchQuery.value.toLowerCase();
  return users.value.filter(user =>
    user.email.toLowerCase().includes(query) ||
    user.first_name.toLowerCase().includes(query) ||
    user.last_name.toLowerCase().includes(query) ||
    user.job_title.toLowerCase().includes(query)
  );
});

onMounted((): void => {
  fetchUsers();
});
</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Users</h1>
        <button class="btn btn-primary btn-sm" @click="addUser()">Add User</button>
      </div>
      <hr>
      <input type="text" v-model="searchQuery" placeholder="Search by email, first name, last name, or job title" class="form-control mb-3">
      <hr>
      <table class="table table-striped shadow-lg">
        <thead>
          <tr>
            <th scope="col">User ID</th>
            <th scope="col">Email</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Job Title</th>
            <th scope="col">Access Level</th>
            <th scope="col">Login Attempts</th>
            <th scope="col">Last Login Attempt</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredUsers" :key="user.user_id">
            <td>{{ user.user_id }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.first_name }}</td>
            <td>{{ user.last_name }}</td>
            <td>{{ user.job_title }}</td>
            <td>{{ user.access_level }}</td>
            <td>{{ user.login_attempts }}</td>
            <td>{{ user.last_login_attempt }}</td>
            <td>
              <button class="btn btn-primary btn-sm" @click="editUser(user.user_id)">Edit</button>
            </td>
            <td>
              <button class="btn btn-danger btn-sm" @click="deleteUser(user.user_id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>