import { createApp } from 'vue'
import '@/styles/style.css'
import App  from '@/App.vue'
import Router from '@/config/router'
import Store from '@/store'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap-icons/font/bootstrap-icons.css';

const app = createApp(App);
app.use(Router);
app.use(Store);
app.mount('#app');
