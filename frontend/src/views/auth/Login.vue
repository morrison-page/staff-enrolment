<script setup lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  import _axios from '@/config/axios';

  const email = ref<string>('');
  const password = ref<string>('');
  const invalidFeedback = ref<string>('');
  const store = useStore();
  const router = useRouter();

  const handleSubmit = async (event: Event) => {
    event.preventDefault();
    try {
      await store.dispatch('auth/login', { email: email.value, password: password.value });
      if (store.getters['auth/isAuthenticated']) {
        router.push('/');
      } else {
        invalidFeedback.value = 'Invalid email or password';
      }
    } catch (error) {
      console.log('An error occurred during login.' + error);
    }
  };

  onMounted(async () => {
    try {
      const response = await _axios.get('/users');
      if (response.status === 401) {
        router.push('/login');
      } else if (store.getters['auth/isAuthenticated']) {
        router.push('/');
      }
    } catch (error) {
      console.log('An error occurred during login.' + error);
    }
  });
</script>

<template>
  <section class="h-100">
    <div class="container h-100">
      <div class="row justify-content-sm-center h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9" style="padding-top: 23vh">
          <div class="card shadow-lg">
            <div class="card-body p-5">
              <h1 class="fs-4 card-title fw-bold mb-4">Staff Enrolment Login</h1>
              <form class="needs-validation" autocomplete="off" @submit="handleSubmit">
                <div class="mb-3">
                  <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                  <input id="email" type="email" class="form-control" name="email" v-model="email" required autofocus>
                </div>
                <div class="mb-3">
                  <div class="mb-2 w-100"><label class="text-muted" for="password">Password</label></div>
                  <input id="password" type="password" class="form-control" name="password" v-model="password" required>
                </div>
                <span class="text-danger">{{ invalidFeedback }}</span>
                <div class="d-flex">
                  <button type="submit" class="btn btn-primary w-100" :class="invalidFeedback.length > 0 ? 'mt-3' : ''">Login</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>

</style>