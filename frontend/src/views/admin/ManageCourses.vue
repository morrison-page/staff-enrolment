<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, computed, watch } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

interface Course {
  course_id: string;
  course_title: string;
  course_date: string;
  course_duration: string;
  max_attendees: number;
  description: string;
}

interface User {
  access_level: string;
}

const store = useStore();
const router = useRouter();
const courses = computed<Course[]>(() => store.state.courses.courses);
const futureSearchQuery = ref<string>('');
const historicSearchQuery = ref<string>('');

// Admin Access Level Check
const access_level: string = (store.getters['auth/user'] as User).access_level;
if (access_level !== 'admin') {
  router.push({ name: 'NotFound' });
}

// Vuex Actions
const fetchCourses = async (): Promise<void> => {
  await store.dispatch('courses/fetchCourses');
};

const deleteCourse = async (courseId: string): Promise<void> => {
  await store.dispatch('courses/deleteCourse', courseId);
  fetchCourses(); // Refresh
};

// Edit Actions
const editCourse = (courseId: string): void => {
  router.push({ path: `/manage/courses/form/${courseId}` });
};
const addCourse = (): void => {
  router.push('/manage/courses/form');
};

function isPastDate(date: string): boolean {
  const [day, month, year] = date.split('/').map(Number);
  const inputDate = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to midnight to compare only dates
  return inputDate < today;
}

const filteredFutureCourses = computed<Course[]>(() => {
  if (!futureSearchQuery.value) {
    return courses.value.filter(course => !isPastDate(course.course_date));
  }
  const query = futureSearchQuery.value.toLowerCase();
  return courses.value.filter(course =>
    (!isPastDate(course.course_date)) &&
    (course.course_title.toLowerCase().includes(query) || course.course_date.includes(query))
  );
});

const filteredHistoricCourses = computed<Course[]>(() => {
  if (!historicSearchQuery.value) {
    return courses.value.filter(course => isPastDate(course.course_date));
  }
  const query = historicSearchQuery.value.toLowerCase();
  return courses.value.filter(course =>
    (isPastDate(course.course_date)) &&
    (course.course_title.toLowerCase().includes(query) || course.course_date.includes(query))
  );
});

watch(router, fetchCourses, { immediate: true });

onMounted(() => {
  fetchCourses();
});
</script>

<template>
  <AppLayout>
    <div class="container mt-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Future Courses</h2>
        <button class="btn btn-primary btn-sm" @click="addCourse()">Add Course</button>  
      </div>
      <hr>
      <input type="text" v-model="futureSearchQuery" placeholder="Search by course title or date" class="form-control mb-3">
      <hr>
      <table class="table table-striped shadow-lg">
        <thead>
          <tr>
            <th scope="col">Course ID</th>
            <th scope="col">Course Title</th>
            <th scope="col">Course Date</th>
            <th scope="col">Course Duration</th>
            <th scope="col">Max Attendees</th>
            <th scope="col">Description</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="course in filteredFutureCourses" :key="course.course_id">
            <tr>
              <td>{{ course.course_id }}</td>
              <td>{{ course.course_title }}</td>
              <td>{{ course.course_date }}</td>
              <td>{{ course.course_duration }}</td>
              <td>{{ course.max_attendees }}</td>
              <td>{{ course.description }}</td>
              <td><button class="btn btn-primary btn-sm" @click="editCourse(course.course_id)">Edit</button></td>
              <td><button class="btn btn-danger btn-sm" @click="deleteCourse(course.course_id)">Delete</button></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <br>
    <div class="container mt-2">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Historic Courses</h2>
        <button class="btn btn-primary btn-sm" @click="addCourse()">Add Course</button>
      </div>
      <hr>
      <input type="text" v-model="historicSearchQuery" placeholder="Search by course title or date" class="form-control mb-3">
      <hr>
      <table class="table table-striped shadow-lg">
        <thead>
          <tr>
            <th scope="col">Course ID</th>
            <th scope="col">Course Title</th>
            <th scope="col">Course Date</th>
            <th scope="col">Course Duration</th>
            <th scope="col">Max Attendees</th>
            <th scope="col">Description</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="course in filteredHistoricCourses" :key="course.course_id">
            <tr>
              <td>{{ course.course_id }}</td>
              <td>{{ course.course_title }}</td>
              <td>{{ course.course_date }}</td>
              <td>{{ course.course_duration }}</td>
              <td>{{ course.max_attendees }}</td>
              <td>{{ course.description }}</td>
              <td><button class="btn btn-primary btn-sm" @click="editCourse(course.course_id)">Edit</button></td>
              <td><button class="btn btn-danger btn-sm" @click="deleteCourse(course.course_id)">Delete</button></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>