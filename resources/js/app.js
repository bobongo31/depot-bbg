import '../css/app.css'; // Utilisation de Tailwind CSS
import './bootstrap';
import { createApp } from 'vue';
import Courriers from './components/Courriers.vue';
import Login from './components/Login.vue';
import { createRouter, createWebHistory } from 'vue-router';

// Définir les routes
const routes = [
  { path: '/', component: Courriers },
  { path: '/login', component: Login },
  { path: '/home', component: Courriers }, // Ajout de la route /home
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp({});
app.component('courriers', Courriers);
app.component('login', Login);
app.use(router); // Ajout du routeur (si utilisé)
app.mount('#app');
