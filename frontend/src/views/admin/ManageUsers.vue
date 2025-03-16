<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const users = computed(() => store.state.users.users);

// Admin Access Level Check
const access_level = store.getters['auth/user'].access_level;
if (access_level !== 'admin') {
  router.push({name: 'NotFound'});
}

// Vuex Actions
const fetchUsers = async () => {
  await store.dispatch('users/fetchUsers');
};

const deleteUser = async (userId: string) => {
  await store.dispatch('users/deleteUser', userId);
  fetchUsers(); // Refresh
};

// Edit Actions
const editUser = (userId: string) => {
  router.push({ path: `/manage/users/form/${userId}` });
};
const addUser = () => {
  router.push('/manage/users/form');
};

onMounted(() => {
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
          <tr v-for="user in users" :key="user.user_id">
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