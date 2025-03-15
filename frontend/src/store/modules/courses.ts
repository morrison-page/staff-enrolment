import { Module } from 'vuex';
import _axios from '@/config/axios';

interface Course {
  course_id: string;
  course_title: string;
  course_date: string;
  course_duration: number;
  max_attendees: number;
  total_attendees: number;
  course_description: string;
}

interface CourseState {
  courses: Course[];
  selectedCourse: Course | null;
}

const state: CourseState = {
  courses: [],
  selectedCourse: null,
};

const mutations = {
  setCourses(state: CourseState, courses: Course[]) {
    state.courses = courses;
  },

  setSelectedCourse(state: CourseState, course: Course) {
    state.selectedCourse = course;
  },

  updateCourse(state: CourseState, updatedCourse: Course) {
    const index = state.courses.findIndex(course => course.course_id === updatedCourse.course_id);
    if (index !== -1) {
      state.courses.splice(index, 1, updatedCourse);
    }
  },

  deleteCourse(state: CourseState, courseId: string) {
    state.courses = state.courses.filter(course => course.course_id !== courseId);
  },

  addCourse(state: CourseState, newCourse: Course) {
    state.courses.push(newCourse);
  },
};

const actions = {
  async fetchCourses({ commit }: { commit: Function }) {
    try {
      const response = await _axios.get('/courses');
      const data: Course[] = response.data;
      commit('setCourses', data);
    } catch (error) {
      console.error('Error fetching courses', error);
    }
  },

  async fetchCourse({ commit }: { commit: Function }, courseId: string) {
    try {
      const response = await _axios.get(`/courses/${courseId}`);
      const data: Course = response.data;
      commit('setSelectedCourse', data);
    } catch (error) {
      console.error('Error fetching course', error);
    }
  },
  
  async updateCourse({ commit }: { commit: Function }, updatedCourse: Course) {
    try {
      await _axios.put(`/courses/${updatedCourse.course_id}`, updatedCourse);
      commit('updateCourse', updatedCourse);
    } catch (error) {
      console.error('Error updating course', error);
    }
  },

  async deleteCourse({ commit }: { commit: Function }, courseId: string) {
    try {
      await _axios.delete(`/courses/${courseId}`);
      commit('deleteCourse', courseId);
    } catch (error) {
      console.error('Error deleting course', error);
    }
  },

  async createCourse({ commit }: { commit: Function }, newCourse: Course) {
    try {
      const response = await _axios.post('/courses', newCourse);
      const createdCourse: Course = response.data;
      commit('addCourse', createdCourse);
    } catch (error) {
      console.error('Error creating course', error);
    }
  },
};

const getters = {
  allCourses: (state: CourseState) => state.courses,
  selectedCourse: (state: CourseState) => state.selectedCourse,
};

const coursesModule: Module<CourseState, any> = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};

export default coursesModule;