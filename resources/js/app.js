import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

import CommentList from './components/CommentList.vue';
import CommentForm from './components/CommentForm.vue';
import RegisterForm from './components/RegisterForm.vue';
import LoginForm from './components/LoginForm.vue';

// Define your routes
const routes = [
    { path: '/', component: CommentList },
    { path: '/register', component: RegisterForm },
    { path: '/login', component: LoginForm }
];

// Create the router instance and pass the `routes` option
const router = createRouter({
    history: createWebHistory(),
    routes
});

// Create the Vue app instance
const app = createApp({});

// Register components
app.component('comment-list', CommentList);
app.component('comment-form', CommentForm);
app.component('register-form', RegisterForm);
app.component('login-form', LoginForm);
// Use the router
app.use(router);

// Mount the app
app.mount('#app');
