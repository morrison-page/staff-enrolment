import { createRouter, createWebHistory } from 'vue-router';
// Auth Views
import Login from '@/views/auth/Login.vue';
// User Views
import Home from '@/views/user/Home.vue';
import YourCourses from '@/views/user/YourCourses.vue';
// Admin Views
import ManageUsers from '@/views/admin/ManageUsers.vue';
import UserForm from '@/views/admin/forms/UserForm.vue';
import ManageCourses from '@/views/admin/ManageCourses.vue';
import CourseForm from '@/views/admin/forms/CourseForm.vue';
import ManageEnrolments from '@/views/admin/ManageEnrolments.vue';
// Other Views
import NotFound from '@/views/other/NotFound.vue';

const routes = [
  // Auth Routes
  { path: '/login', component: Login },
  // User Routes
  { path: '/', component: Home },
  { path: '/yourcourses', component: YourCourses  },
  // Admin Routes
  { path: '/manage/users', component: ManageUsers },
  { path: '/manage/users/form/:userId?', name: 'UsersForm', component: UserForm, props: true },
  { path: '/manage/courses', component: ManageCourses },
  { path: '/manage/courses/form/:courseId?', name: 'CoursesForm', component: CourseForm, props: true },
  { path: '/manage/enrolments', component: ManageEnrolments },
  // Other Routes
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;