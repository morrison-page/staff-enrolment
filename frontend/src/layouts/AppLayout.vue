<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Sidebar from '@/components/Sidebar.vue';
import MobileSidebar from '@/components/MobileSidebar.vue';

const isMobile = ref(false);

function checkIfMobile() {
  isMobile.value = window.innerWidth <= 768;
}

onMounted(() => {
  checkIfMobile();
  window.addEventListener('resize', checkIfMobile);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkIfMobile);
});
</script>

<template>
  <div :class="['app-container', { 'mobile-layout': isMobile }]">
    <Sidebar v-if="!isMobile"/>
    <MobileSidebar v-if="isMobile"/>
    <main>
      <slot></slot>
    </main>
  </div>
</template>

<style scoped>
.app-container {
  display: flex;
}

main {
  flex: 1;
  margin-left: 17.5rem; /* Same width as the sidebar */
  padding: 1rem;
}

.mobile-layout {
  flex-direction: column;
}

.mobile-layout main {
  margin-left: 0;
  padding: 0.5rem;
}
</style>