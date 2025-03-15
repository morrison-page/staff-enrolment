import { Module } from 'vuex';
import _axios from '@/config/axios';

interface Enrolment {
  enrolment_id: string;
  user: string;
  email: string;
  course_id: string;
  course_title: string;
  course_date: string;
}

interface EnrolmentState {
  enrolments: Enrolment[];
}

const state: EnrolmentState = {
  enrolments: [],
};

const mutations = {
  addEnrolment(state: EnrolmentState, enrolment: Enrolment) {
    state.enrolments.push(enrolment);
  },
  removeEnrolment(state: EnrolmentState, enrolmentId: string) {
    state.enrolments = state.enrolments.filter(enrolment => enrolment.enrolment_id !== enrolmentId);
  },
};

const actions = {
  async createEnrolment({ commit }: { commit: Function}, newEnrolment: Enrolment) {
    try {
      const response = await _axios.post('/enrolments', newEnrolment);
      const createdEnrolment: Enrolment = response.data;
      commit('addEnrolment', createdEnrolment);
    } catch (error) {
      console.error('Error creating enrolment', error);
    }
  },

  async deleteEnrolment({ commit }: { commit: Function }, deletedEnrolment: Enrolment) {
    try {
      const response = await _axios.delete(`/enrolments`, { data: deletedEnrolment });
      if (response.status === 200) {
        commit('removeEnrolment', deletedEnrolment);
      } else {
        console.error('Error deleting enrolment', response.data);
      }
    } catch (error) {
      console.error('Error deleting enrolment', error);
    }
  },
};

const getters = {
  allEnrolments: (state: EnrolmentState) => state.enrolments,
};

const enrolmentsModule: Module<EnrolmentState, any> = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};

export default enrolmentsModule;