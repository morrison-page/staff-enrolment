<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import _axios from '@/config/axios';

const route = useRoute();
const router = useRouter();
const store = useStore();

const courseId = route.params.courseId;
const isEditMode = ref(!!courseId);
const course = ref({
  course_id: '',
  course_title: '',
  course_date: '',
  course_duration: 0,
  max_attendees: 0,
  description: '',
});

const formRef = ref(null);

const dateToHTMLdate = (dateString) => {
  if (!dateString) return ''; // Dodgy Type Fix
  const [day, month, year] = dateString.split('/');
  return `${year}-${month}-${day}`;
};
const htmlDateToDate = (dateString) => {
  const [year, month, day] = dateString.split('-');
  return `${day}/${month}/${year}`;
};

// TODO: Refactor to use vuex
const fetchCourse = async () => {
  try {
    const response = await _axios.get(`/courses/${courseId}`);
    // Populate the form
    course.value = {
      course_id: response.data[0].course_id,
      course_title: response.data[0].course_title,
      course_date: dateToHTMLdate(response.data[0].course_date),
      course_duration: response.data[0].course_duration,
      max_attendees: response.data[0].max_attendees,
      description: response.data[0].description,
    };
  } catch (error) {
    console.error("Error fetching course data: ", error);
  }
};

const handleCancel = () => { router.push('/manage/courses'); };

const handleSubmit = async () => {
  if (formRef.value.checkValidity() === false) {
    formRef.value.classList.add('was-validated');
    return;
  }

  if (isEditMode.value) {
    course.value.course_date = htmlDateToDate(course.value.course_date);
    await store.dispatch('courses/updateCourse', course.value);
  } else {
    delete course.value.course_id;
    course.value.course_date = htmlDateToDate(course.value.course_date);
    await store.dispatch('courses/createCourse', course.value);
  }
  router.push('/manage/courses');
};

const loadCourse = async () => {
  if (isEditMode.value) {
    fetchCourse();
  }
};

onMounted(loadCourse);

// Refresh for component state on cancel for an edit form
watch(route, async (newRoute) => {
  courseId = newRoute.params.courseId;
  isEditMode.value = !!courseId;
  await loadCourse();
});
</script>

<template>
  <AppLayout>
    <div class="card p-4 mt-5">
      <h1>{{ isEditMode ? 'Edit Course' : 'Create Course' }}</h1>
      <hr>
      <form ref="formRef" @submit.prevent="handleSubmit" novalidate>
        <div class="mb-3">
          <label for="course_id" class="form-label">Course ID</label>
          <input :value="course.course_id" id="course_title" type="text" class="form-control" disabled/>
        </div>
        <div class="mb-3">
          <label for="course_title" class="form-label">Course Title</label>
          <input v-model="course.course_title" id="course_title" type="text" class="form-control" required minlength="5" maxlength="255"/>
          <div class="invalid-feedback">Please provide a course title (5-255 characters).</div>
        </div>
        <div class="mb-3">
          <label for="course_date" class="form-label">Course Date</label>
          <input v-model="course.course_date" id="course_date" type="date" class="form-control" required minlength="10" maxlength="10"/>
          <div class="invalid-feedback">Please provide a valid course date (DD/MM/YYYY)</div>
        </div>
        <div class="mb-3">
          <label for="course_duration" class="form-label">Course Duration (days)</label>
          <input v-model="course.course_duration" id="course_duration" type="number" class="form-control" required min="1" max="365"/>
          <div class="invalid-feedback">Please provide a course duration (1-365 days).</div>
        </div>
        <div class="mb-3">
          <label for="max_attendees" class="form-label">Max Attendees</label>
          <input v-model="course.max_attendees" id="max_attendees" type="number" class="form-control" required min="1" max="10000"/>
          <div class="invalid-feedback">Please provide the maximum number of attendees (1-10,000).</div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea v-model="course.description" style="max-height: 200px;" id="description" class="form-control" required minlength="2" maxlength="100"></textarea>
            <div class="invalid-feedback">Please provide a description (2-100 characters).</div>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2">
          <button class="btn btn-secondary w-100" type="button" @click="handleCancel">Cancel</button>
          <button class="btn btn-primary w-100" type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<style scoped>
.card {
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
}
</style>