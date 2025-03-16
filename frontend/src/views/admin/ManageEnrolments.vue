<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import axios from 'axios';


// Admin Access Level Check
const store = useStore();
const router = useRouter();
const access_level = store.getters['auth/user'].access_level;
if (access_level !== 'admin') {
  router.push({name: 'NotFound'});
}

const enrolments = ref([]);
const groupedEnrolments = ref({});

// TODO: Refactor to use vuex
const fetchEnrolments = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/enrolments`, {
      withCredentials: true,
    });
    enrolments.value = response.data;
    groupEnrolmentsByCourse();
  } catch (error) {
    console.error('Error fetching enrolments:', error);
  }
};
// TODO: Refactor to use vuex
const deleteEnrolment = async (enrolmentId) => {
  try {
    await axios.delete(`${import.meta.env.VITE_BACKEND_API_URL}/enrolments/${enrolmentId}`, {
      withCredentials: true,
    });
    await fetchEnrolments();
  } catch (error) {
    console.error('Error deleting enrolment:', error);
  }
};

const groupEnrolmentsByCourse = () => {
  groupedEnrolments.value = enrolments.value.reduce((acc, enrolment) => {
    if (!acc[enrolment.course_id]) {
      acc[enrolment.course_id] = [];
    }
    acc[enrolment.course_id].push(enrolment);
    return acc;
  }, {});
};

onMounted(() => {
  fetchEnrolments();
});
</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <h1>Manage Enrolments</h1>
      <br>
      <div v-for="(enrolments, courseId) in groupedEnrolments" :key="courseId">
        <h4>{{ enrolments[0].course_title }} - {{ enrolments[0].course_date }}</h4>
        <hr>
        <table class="table table-striped shadow-lg">
          <caption>{{ enrolments[0].course_id }}</caption>
          <thead>
            <tr>
              <th scope="col">Enrolment ID</th>
              <th scope="col">User</th>
              <th scope="col">Email</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="enrolment in enrolments" :key="enrolment.user_id">
              <td>{{ enrolment.enrolment_id }}</td>
              <td>{{ enrolment.user }}</td>
              <td>{{ enrolment.email }}</td>
              <td>
                <button class="btn btn-danger btn-sm" @click="deleteEnrolment(enrolment.enrolment_id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
        <br>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>