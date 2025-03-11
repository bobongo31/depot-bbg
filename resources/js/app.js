import '../css/app.css'; // Utilisation de Tailwind CSS
import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

// Importer jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// Vérifier si jQuery est bien chargé
$(document).ready(function() {
    console.log("✅ jQuery est bien chargé !");
});

// Importer les composants Vue
import Courriers from './components/Courriers.vue';
import Login from './components/Login.vue';

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
app.use(router); // Ajout du routeur Vue
app.mount('#app');
