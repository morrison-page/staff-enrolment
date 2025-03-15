import { Module } from 'vuex';
import _axios from '@/config/axios';

interface User {
  user_id: string;
  email: string;
  first_name: string;
  last_name: string;
  job_title: string;
  access_level: string;
  login_attempts: number;
  last_login_attempt: string;
}

interface UserState {
  users: User[];
  selectedUser: User | null;
}

const state: UserState = {
  users: [],
  selectedUser: null,
};

const mutations = {
  setUsers(state: UserState, users: User[]) {
    state.users = users;
  },

  setSelectedUser(state: UserState, user: User) {
    state.selectedUser = user;
  },

  updateUser(state: UserState, updatedUser: User) {
    const index = state.users.findIndex(user => user.user_id === updatedUser.user_id);
    if (index !== -1) {
      state.users.splice(index, 1, updatedUser);
    }
  },

  deleteUser(state: UserState, userId: string) {
    state.users = state.users.filter(user => user.user_id !== userId);
  },

  addUser(state: UserState, newUser: User) {
    state.users.push(newUser);
  },
};

const actions = {
  async fetchUsers({ commit }: { commit: Function }) {
    try {
      const response = await _axios.get('/users');
      commit('setUsers', response.data);
    } catch (error) {
      console.error('Error fetching users', error);
    }
  },
  
  async fetchUser({ commit }: { commit: Function }, userId: string) {
    try {
      const response = await _axios.get(`/users/${userId}`);
      commit('setSelectedUser', response.data);
    } catch (error) {
      console.error('Error fetching user', error);
    }
  },
  
  async updateUser({ commit }: { commit: Function }, updatedUser: User) {
    try {
      await _axios.put(`/users/${updatedUser.user_id}`, updatedUser);
      commit('updateUser', updatedUser);
    } catch (error) {
      console.error('Error updating user', error);
    }
  },

  async deleteUser({ commit }: { commit: Function }, userId: string) {
    try {
      await _axios.delete(`/users/${userId}`);
      commit('deleteUser', userId);
    } catch (error) {
      console.error('Error deleting user', error);
    }
  },

  async createUser({ commit }: { commit: Function }, newUser: User) {
    try {
      const response = await _axios.post('/users', newUser);
      commit('addUser', response.data);
    } catch (error) {
      console.error('Error creating user', error);
    }
  },
};

const getters = {
  allUsers: (state: UserState) => state.users,
  selectedUser: (state: UserState) => state.selectedUser,
};

const usersModule: Module<UserState, any> = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};

export default usersModule;