<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import _axios from '@/config/axios';
import AppLayout from '@/layouts/AppLayout.vue';
import CourseCard from '@/components/CourseCard.vue';


interface Course {
  course_id: string;
  course_title: string;
  course_date: string;
  course_duration: number;
  max_attendees: number;
  total_attendees: number;
  description: string;
}

const courses = ref<Course[]>([]);
const searchQuery = ref('');

const fetchCourses = async () => {
  try {
    const response = await _axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/courses`, { withCredentials: true });
    courses.value = response.data;
  } catch (error) {
    console.error('Error fetching courses', error);
  }
};

onMounted(async () => {
  await fetchCourses();
});

function isPastDate(date: string): boolean {
  const [day, month, year] = date.split('/').map(Number);
  const inputDate = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to midnight to compare only dates
  return inputDate < today;
}

const futureCourses = computed(() => courses.value.filter(course => !isPastDate(course.course_date)));

const filteredCourses = computed(() => {
  return futureCourses.value.filter(course =>
    course.course_title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    course.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    course.course_date.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>
  <AppLayout>
    <div class="container mb-4 mt-3">
      <h2>Search Courses</h2>
      <input
        type="text"
        v-model="searchQuery"
        class="form-control"
        placeholder="Search for courses..."
      />
    </div>
    <div class="container mt-5">
      <div v-if="filteredCourses.length >= 0" class="row">
        <div v-for="course in filteredCourses" :key="course.course_id" class="col-md-3 mb-4">
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
      <p v-else class="text-muted">There are no courses available</p>
    </div>
  </AppLayout>
</template>


<style scoped>
h2 {
  padding: 0;
}

@media (min-width: 768px) and (max-width: 991px) {
  .col-md-3 {
    flex: 0 0 50% !important;
    max-width: 50% !important;
  }
}

@media (min-width: 991px) and (max-width: 1366px) {
  .col-md-3 {
    flex: 0 0 33.3333% !important;
    max-width: 33.3333% !important;
  }
}
</style>