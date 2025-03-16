<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const courses = computed(() => store.state.courses.courses);

// Admin Access Level Check
const access_level = store.getters['auth/user'].access_level;
if (access_level !== 'admin') {
  router.push({name: 'NotFound'});
}

// Vuex Actions
const fetchCourses = async () => {
  await store.dispatch('courses/fetchCourses');
};

const deleteCourse = async (courseId: string) => {
  await store.dispatch('courses/deleteCourse', courseId);
  fetchCourses(); // Refresh
};

// Edit Actions
const editCourse = (courseId: string) => {
  router.push({ path: `/manage/courses/form/${courseId}` });
};
const addCourse = () => {
  router.push('/manage/courses/form');
};

onMounted(() => {
  fetchCourses();
});
</script>

<template>
  <AppLayout>
    <div class="container mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Courses</h1>
        <button class="btn btn-primary btn-sm" @click="addCourse()">Add Course</button>
      </div>
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
          <tr v-for="course in courses" :key="course.course_id">
            <td>{{ course.course_id }}</td>
            <td>{{ course.course_title }}</td>
            <td>{{ course.course_date }}</td>
            <td>{{ course.course_duration }}</td>
            <td>{{ course.max_attendees }}</td>
            <td>{{ course.description }}</td>
            <td>
              <button class="btn btn-primary btn-sm" @click="editCourse(course.course_id)">Edit</button>
            </td>
            <td>  
              <button class="btn btn-danger btn-sm" @click="deleteCourse(course.course_id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>