/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

import CommentList from './components/CommentList.vue';
import CommentForm from './components/CommentForm.vue';

// Define your routes
const routes = [
    { path: '/', component: CommentList }
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

// Use the router
app.use(router);

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
