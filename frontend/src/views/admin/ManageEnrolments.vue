<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import _axios from '@/config/axios';

// Admin Access Level Check
const store = useStore();
const router = useRouter();
const access_level = store.getters['auth/user'].access_level;
if (access_level !== 'admin') {
  router.push({name: 'NotFound'});
}

interface Enrolment {
  enrolment_id: string;
  user_id: string;
  user: string;
  email: string;
  course_id: string;
  course_title: string;
  course_date: string;
}

const enrolments = ref<Enrolment[]>([]);
const groupedEnrolments = ref<Record<string, Enrolment[]>>({});
const searchQuery = ref<string>('');

// TODO: Refactor to use vuex
const fetchEnrolments = async () => {
  try {
    const response = await _axios.get('/enrolments');
    enrolments.value = response.data;
    groupEnrolmentsByCourse();
  } catch (error) {
    console.error('Error fetching enrolments:', error);
  }
};
// TODO: Refactor to use vuex
const deleteEnrolment = async (enrolmentId: string) => {
  try {
    const enrolment = enrolments.value.find(e => e.enrolment_id === enrolmentId);
    if (enrolment) {
      await _axios.delete('/enrolments', {
      data: {
        course_id: enrolment.course_id,
        user_id: enrolment.user_id
      }
      });
    }
    await fetchEnrolments();
  } catch (error) {
    console.error('Error deleting enrolment:', error);
  }
};

const groupEnrolmentsByCourse = () => {
  groupedEnrolments.value = enrolments.value.reduce((acc: Record<string, Enrolment[]>, enrolment) => {
    if (!acc[enrolment.course_id]) {
      acc[enrolment.course_id] = [];
    }
    acc[enrolment.course_id].push(enrolment);
    return acc;
  }, {});
};

function isPastDate(date: string): boolean {
  const [day, month, year] = date.split('/').map(Number);
  const inputDate = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to midnight to compare only dates
  return inputDate < today;
}

const filteredEnrolments = computed(() => {
  if (!searchQuery.value) {
    return groupedEnrolments.value;
  }
  const query = searchQuery.value.toLowerCase();
  const filtered = Object.keys(groupedEnrolments.value).reduce((acc: Record<string, Enrolment[]>, courseId) => {
    const enrolments = groupedEnrolments.value[courseId].filter(enrolment => 
      enrolment.course_title.toLowerCase().includes(query) || 
      enrolment.course_date.includes(query)
    );
    if (enrolments.length) {
      acc[courseId] = enrolments;
    }
    return acc;
  }, {});
  return filtered;
});

onMounted(() => {
  fetchEnrolments();
});
</script>

<template>
  <AppLayout>
    <div class="container mt-3">
      <h2>Manage Future Enrolments</h2>
      <hr>
      <input type="text" v-model="searchQuery" placeholder="Search by course title or date" class="form-control mb-3">
      <hr><br>
      <div v-for="(enrolments, courseId) in filteredEnrolments" :key="courseId">
        <div v-if="!isPastDate(enrolments[0].course_date)">
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
    </div>
    <div class="container mt-3">
      <h2>Manage Historic Enrolments</h2>
      <hr><br>
      <div v-for="(enrolments, courseId) in filteredEnrolments" :key="courseId">
        <div v-if="isPastDate(enrolments[0].course_date)">
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
    </div>
  </AppLayout>
</template>

<style scoped>

</style>