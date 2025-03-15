import { Module } from 'vuex';
import _axios from '@/config/axios';
import router from '@/config/router';

interface AuthedUser {
  user_id: string;
  access_level: string;
}

interface AuthState {
  user: AuthedUser | null;
}

const state: AuthState = {
  user: null,
};

const mutations = {
  setUser(state: AuthState, user: AuthedUser) {
    state.user = user;
  },

  clearUser(state: AuthState) {
    state.user = null;
  },
};

interface LoginData {
  email: string;
  password: string;
}

const actions = {
  async login({ commit }: { commit: Function }, authData: LoginData) {
    try {
      await _axios.post('/auth', authData);
      const response = await _axios.get('/auth');
      commit('setUser', response.data);
      router.push('/');
    } catch (error) {
      console.error('Error logging in', error);
    }
  },

  async logout({ commit }: { commit: Function }) {
    try {
      await _axios.delete('/auth');
      commit('clearUser');
      router.push('/login');
    } catch (error) {
      console.error('Error logging out', error);
    }
  },
};

const getters = {
  isAuthenticated: (state: AuthState) => !!state.user,
  user: (state: AuthState) => state.user,
};

const authModule: Module<AuthState, any> = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};

export default authModule;