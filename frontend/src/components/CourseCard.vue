<script setup lang="ts">
import { defineProps, computed, ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';
import _axios from '@/config/axios';

interface Enrolment {
  enrolment_id: string;
  user: string;
  user_id: string;
  email: string;
  course_id: string;
  course_title: string;
  course_date: string;
}

const props = defineProps({
  courseId: {
    type: String,
    required: true,
    default: '',
  },
  title: {
    type: String,
    required: true,
    default: 'Course Title'
  },
  date: {
    type: String,
    required: true,
    default: '01/01/1990'
  },
  maxAttendees: {
    type: Number,
    required: true,
    default: 0,
  },
  courseDuration: {
    type: Number,
    required: true,
    default: 0,
  },
  totalAttendees: {
    type: Number,
    required: true,
    default: 0,
  },
  description: {
    type: String,
    required: true,
    default: 'Course Description',
  },
});

const store = useStore();
const userId = computed(() => store.getters['auth/user'].user_id);
const totalAttendees = ref(props.totalAttendees);

// Reactive variable to store the image URL
const courseImage = ref('../assets/card-image.jpg');

// Fetch image from Pexels API
async function fetchCourseImage() {
  try {
    const response = await axios.get(`https://api.pexels.com/v1/search?query=${props.title}`, {
      headers: {
        Authorization: import.meta.env.VITE_PEXELS_API_KEY,
      }
    });
    if (response.data.photos && response.data.photos.length > 0) {
      courseImage.value = response.data.photos[0].src.medium;
    }
  } catch (error) {
    console.error('Error fetching course image:', error);
  }
}

// Call fetchCourseImage on component mount
onMounted(() => {
  fetchCourseImage();
  fetchEnrolments();
});

// Enrolments ------------------------------------------------------------------#
const enrolments = ref<Enrolment[]>([]);
async function fetchEnrolments() {
  try {
    const response = await _axios.get('/enrolments');
    enrolments.value = response.data;
  } catch (error) {
    console.error('Error fetching enrolments:', error);
  }
};

// Component State -------------------------------------------------------------#
function isUnavailable(): boolean {
  return props.totalAttendees >= props.maxAttendees || isPastDate(props.date);
}
const isEnrolled = computed(() => {
  return enrolments.value.some(enrol => enrol.course_id === props.courseId && enrol.user_id === userId.value);
});

// Event Handlers --------------------------------------------------------------#
function handleEnrolment(courseId: string) {
  if (isEnrolled.value) {
    if (isEnrolled.value) { // Unenrol user
      store.dispatch('enrolments/deleteEnrolment', {
        user_id: userId.value,
        course_id: courseId,
      }).then(() => {
        fetchEnrolments();
        totalAttendees.value -= 1;
      });
    }
  } else { // Enrol user
    store.dispatch('enrolments/createEnrolment', {
      user_id: userId.value,
      course_id: courseId,
    }).then(() => {
      fetchEnrolments();
      totalAttendees.value += 1;
    });
  }
}

// Utility ---------------------------------------------------------------------#
function isPastDate(date: string): boolean {
  const [day, month, year] = date.split('/').map(Number);
  const inputDate = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to midnight to compare only dates
  return inputDate < today;
}
</script>

<template>
  <div class="card shadow-sm">
    <img :src="courseImage" class="card-img-top" alt="Course Image">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">{{ props.title }}</h5>
      <p class="card-text description-text">{{ props.description }}</p>
      <p class="card-text">Start: {{ props.date }}</p>
      <p class="card-text">Duration: {{ props.courseDuration }} (Days)</p>
      <div class="d-flex justify-content-between align-items-center mt-auto">
        <button class="btn" :disabled="isUnavailable()" @click="handleEnrolment(props.courseId)" 
          :class="{
            'btn-secondary': isUnavailable(),
            'btn-primary': !isUnavailable(),
            'btn-danger': isEnrolled && !isUnavailable(),
          }">
                    
          {{ isUnavailable() ? 'Course Unavailable' : (isEnrolled ? 'Unenroll' : 'Sign Up') }}
        </button>
        <p class="card-text mb-0">{{ totalAttendees }} / {{ props.maxAttendees }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.description-text {
  height: 4.5rem; /* Exact height for 3 lines */
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Changed to 3 lines exactly */
  line-clamp: 3; /* Standard property for compatibility */
  -webkit-box-orient: vertical;
  margin-bottom: 0.5rem;
}

.card {
  padding: 0;
}

.card-img-top {
  width: 100%;
  height: 150px; /* Set a fixed height */
  object-fit: cover; /* Ensure the image covers the area without distortion */
  object-position: center; /* Center the image within the frame */
  object-fit: cover; /* Ensure the image covers the area without distortion */
}

.card-title {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>