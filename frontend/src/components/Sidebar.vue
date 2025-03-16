<script setup lang="ts">
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const route = useRoute();
const store = useStore();

const autheduser = store.getters['auth/user'];

const handleLogout = () => {
  store.dispatch('auth/logout');
};
</script>

<template>
  <nav class="d-flex flex-column flex-shrink-0 p-3 sidebar" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <i class="bi bi-graph-up-arrow pe-none ms-2 me-3" width="40" height="32"></i>
      <span class="fs-4">Enrolment App</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <router-link :class="['nav-link', route.path === '/' ? 'active' : 'link-body-emphasis']" to="/">
          <i class="bi pe-none me-2 bi-house-fill"></i>
          Home
        </router-link>
      </li>
      <li>
        <router-link :class="['nav-link', route.path === '/yourcourses' ? 'active' : 'link-body-emphasis']" to="/yourcourses">
          <i class="bi pe-none me-2 bi-card-list"></i>
          Your Courses
        </router-link>
      </li>
      <template v-if="autheduser.access_level === 'admin'">
        <hr>
        <li>
          <router-link :class="['nav-link', route.path === '/manage/users' ? 'active' : 'link-body-emphasis']" to="/manage/users">
              <i class="bi bi-people-fill pe-none me-2"></i>
              Manage Users
            </router-link>
        </li>
        <li>
          <router-link :class="['nav-link', route.path === '/manage/courses' ? 'active' : 'link-body-emphasis']" to="/manage/courses">
              <i class="bi bi-pencil-fill pe-none me-2"></i>
              Manage Courses
            </router-link>
        </li>
        <li>
          <router-link :class="['nav-link', route.path === '/manage/enrolments' ? 'active' : 'link-body-emphasis']" to="/manage/enrolments">
            <i class="bi bi-speedometer2 pe-none me-2"></i>
            Manage Enrolments
          </router-link>
        </li>
      </template>
      <hr>
    </ul>
    <hr>
    <button class="btn btn-danger" @click="handleLogout">
      <i class="bi bi-box-arrow-right"></i>
      <strong> Logout</strong>
    </button>
  </nav>
</template>

<style scoped>
.sidebar {
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 100;
}

[data-bs-theme="light"].sidebar {
  border-right: 1px solid #c2c4c5!important;
}

[data-bs-theme="dark"] .sidebar {
  border-right: 1px solid #495057;
}

[data-bs-theme="light"] nav {
  background-color: #f8f9fa;
}

[data-bs-theme="dark"] nav {
  background-color: #212529;
}
</style>