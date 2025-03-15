import axios from 'axios';
import router from '@/config/router'; // Import the router instance

const _axios = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_API_URL,
  withCredentials: true,
});

_axios.interceptors.response.use(
  response => response,
  async error => {
    if (error.response && error.response.status === 401) {
      await _axios.delete('/auth');
      if (router.currentRoute.value.path !== '/login') {
        router.push('/login');
      }
    }
    return Promise.reject(error);
  }
);

export default _axios;