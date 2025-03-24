<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import _axios from '@/config/axios';

const route = useRoute();
const router = useRouter();
const store = useStore();

const userId = route.params.userId;
const isEditMode = ref(!!userId);
const user = ref({
  user_id: 'N/a',
  email: '',
  first_name: '',
  last_name: '',
  job_title: '',
  access_level: '',
  password: '',
  login_attempts: 0,
  last_login_attempt: 'N/a',
});

const formRef = ref(null);
const matchingPassword = ref('');

const fetchUser = async () => {
  try {
    const response = await _axios.get(`/users/${userId}`);
    user.value = response.data[0]; // Populate the form
  } catch (error) {
    console.error("Error fetching user data: ", error);
  }
};

const handleCancel = () => { router.push('/manage/users'); };

const validatePassword = (password) => {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,50}$/;
  return regex.test(password);
};

const handleSubmit = async () => {
  const form = formRef.value;
  const passwordInput = form.querySelector('#password');
  passwordInput.setCustomValidity('');

  if (form.checkValidity() === false || user.value.password !== matchingPassword.value || !validatePassword(user.value.password)) {
    if (!validatePassword(user.value.password)) {
      passwordInput.setCustomValidity('Password must be 8-50 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
    }
    form.classList.add('was-validated');
    return;
  }

  form.classList.remove('was-validated');

  if (isEditMode.value) {
    delete user.value.password;
    delete user.value.login_attempts;
    delete user.value.last_login_attempt;
    await store.dispatch('users/updateUser', user.value);
  } else {
    delete user.value.user_id;
    delete user.value.login_attempts;
    delete user.value.last_login_attempt;
    await store.dispatch('users/createUser', user.value);
  }
  router.push('/manage/users');
};

const loadUser = async () => {
  if (isEditMode.value) {
    fetchUser();
  }
};

onMounted(loadUser);

// Watch for changes in the password field and validate it in real-time
watch(() => user.value.password, (newPassword) => {
  const passwordInput = formRef.value.querySelector('#password');
  if (!validatePassword(newPassword)) {
    passwordInput.setCustomValidity('Password must be 8-50 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
  } else {
    passwordInput.setCustomValidity('');
  }
});

// Refresh for component state on cancel for an edit form
watch(route, async (newRoute) => {
  userId = newRoute.params.userId;
  isEditMode.value = !!userId;
  await loadUser();
});
</script>

<template>
  <AppLayout>
    <div class="card p-4 mt-5">
      <h1>{{ isEditMode ? 'Edit User' : 'Create User' }}</h1>
      <hr>
      <form ref="formRef" @submit.prevent="handleSubmit" novalidate>
        <div class="mb-3">
          <label for="user_id" class="form-label">User ID</label>
          <input :value="user.user_id" id="user_id" type="text" class="form-control" disabled/>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input v-model="user.email" id="email" type="email" class="form-control" required/>
          <div class="invalid-feedback">Please provide a valid email address.</div>
        </div>
        <div class="row">
          <div class="col-md-6 col-12 mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input v-model="user.first_name" id="first_name" type="text" class="form-control" required minlength="2" maxlength="50"/>
            <div class="invalid-feedback">Please provide a first name (2-50 characters).</div>
          </div>
          <div class="col-md-6 col-12 mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input v-model="user.last_name" id="last_name" type="text" class="form-control" required minlength="2" maxlength="50"/>
            <div class="invalid-feedback">Please provide a last name (2-50 characters).</div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8 col-12 mb-3">
            <label for="job_title" class="form-label">Job Title</label>
            <input v-model="user.job_title" id="job_title" type="text" class="form-control" required minlength="2" maxlength="100"/>
            <div class="invalid-feedback">Please provide a job title (2-100 characters).</div>
          </div>
          <div class="col-md-4 col-12 mb-3">
              <label for="access_level" class="form-label">Access Level</label>
              <select v-model="user.access_level" id="access_level" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
              <div class="invalid-feedback">Please select an access level.</div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 col-12 mb-3">
              <label for="login_attempts" class="form-label">Login Attempts</label>
              <input :value="user.login_attempts" id="login_attempts" class="form-control" disabled/>
          </div>
            <div :class="isEditMode ? 'col-md-9 col-12 mb-4' : 'col-md-9 col-12 mb-3'">
              <label for="last_login_attempt" class="form-label">Last Login Attempt</label>
              <input :value="user.last_login_attempt" id="last_login_attempt" class="form-control" disabled/>
          </div>
        </div>
        <div v-if="!isEditMode" class="row">
          <div class="col-12 mb-4">
            <label for="password" class="form-label">Password</label>
            <input v-model="user.password" id="password" type="password" class="form-control" required minlength="8" maxlength="50"/>
            <div class="invalid-feedback">Password must be 8-50 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</div>
          </div>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2">
          <button class="btn btn-secondary w-100" type="button" @click="handleCancel">Cancel</button>
          <button class="btn btn-primary w-100" type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<style scoped>
.card {
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
}
</style>