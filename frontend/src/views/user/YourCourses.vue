<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import CourseCard from '@/components/CourseCard.vue';
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import _axios from '@/config/axios';

interface Course {
  course_id: string;
  course_title: string;
  course_date: string;
  course_duration: number;
  max_attendees: number;
  total_attendees: number;
  description: string;
}

function isPastDate(date: string): boolean {
  const [day, month, year] = date.split('/').map(Number);
  const inputDate = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to midnight to compare only dates
  return inputDate < today;
}

const store = useStore();
const userId = store.getters['auth/user'].user_id;

const userCourses = ref<Course[]>([]);

onMounted(async () => {
  // TODO: Refactor to use vuex
  try {
    const response = await _axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/users/${userId}/courses`);
    userCourses.value = response.data;
  } catch (error) {
    console.error('Error fetching user courses:', error);
  }
});

const currentCourses = computed(() => userCourses.value.filter(course => !isPastDate(course.course_date)));
const pastCourses = computed(() => userCourses.value.filter(course => isPastDate(course.course_date)));

</script>

<template>
  <AppLayout>
    <div class="container mt-3">
      <h2 class="mb-4">Current Enrolments</h2>
      <div v-if="userCourses.length > 0" class="row">
        <div v-for="course in currentCourses" :key="course.course_id" class="col-md-3 mb-4">
          <CourseCard
            :courseId="course.course_id"
            :title="course.course_title"
            :date="course.course_date"
            :maxAttendees="course.max_attendees"
            :courseDuration="course.course_duration"
            :totalAttendees ="course.total_attendees"
            :description="course.description"
          />
        </div>
      </div>
      <p v-else class="text-muted">You are not enroled on any courses</p>
    </div>
    <div class="container mt-2">
      <h2 class="mb-4">Course History</h2>
      <div v-if="pastCourses.length > 0" class="row">
        <div v-for="course in pastCourses" :key="course.course_id" class="col-md-3 mb-4">
          <CourseCard
            :courseId="course.course_id"
            :title="course.course_title"
            :date="course.course_date"
            :maxAttendees="course.max_attendees"
            :courseDuration="course.course_duration"
            :totalAttendees ="course.total_attendees"
            :description="course.description"
          />
        </div>
      </div>
      <p v-else class="text-muted">You haven't completed any courses</p>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>