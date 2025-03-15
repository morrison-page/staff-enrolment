import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import usersModule from '@/store/modules/users';
import coursesModule from '@/store/modules/courses';
import authModule from '@/store/modules/auth';
import enrolmentsModule from '@/store/modules/enrolments';

const store = createStore({
  modules: {
    users: usersModule,
    courses: coursesModule,
    auth: authModule,
    enrolments: enrolmentsModule
  },
  plugins: [createPersistedState({
    paths: ['auth']
  })],
});

export default store;