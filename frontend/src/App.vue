<script setup lang="ts">
import { onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();

onMounted(() => {
  const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
  document.body.setAttribute('data-bs-theme', prefersDarkScheme ? 'dark' : 'light');

  if (!store.getters['auth/isAuthenticated']) {
    router.push('/login');
  }
});
</script>

<template>
  <router-view></router-view>
</template>