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
  <nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <i class="bi bi-graph-up-arrow pe-none ms-2 me-3" width="40" height="32"></i>
    <a class="navbar-brand" href="/">Enrolment App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <hr>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
      <hr>
      <template v-if="autheduser.access_level === 'admin'">
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
        <hr>
      </template>
      </ul>
      <button class="btn btn-danger mb-3" id="logout-button" @click="handleLogout">
        <i class="bi bi-box-arrow-right"></i>
        <strong> Logout</strong>
      </button>
    </div>
  </div>
</nav>
</template>

<style scoped>
.sidebar {
  position: fixed;
  bottom: 0;
  width: 100vw;
  z-index: 100;
}

#logout-button {
  width: 100%;
}

[data-bs-theme="light"] nav {
  background-color: #f8f9fa!important;
  border-bottom: 1px solid #c2c4c5!important;
}

[data-bs-theme="dark"] nav {
  background-color: #212529!important;
  border-bottom: 1px solid #495057!important;
}
</style>