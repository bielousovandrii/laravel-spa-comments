<template>
    <nav class="navbar">
        <router-link to="/">Comments</router-link>
        <router-link to="/register">Register</router-link>
        <router-link to="/login">Login</router-link>
    </nav>
    <div class="max-w-md mx-auto mt-8">
        <form @submit.prevent="login" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input id="email" type="email" v-model="form.email" placeholder="Enter your email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input id="password" type="password" v-model="form.password" placeholder="Enter your password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
            </div>
        </form>
        <div v-if="errors.length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <ul>
                <li v-for="(error, index) in errors" :key="index" class="list-disc">{{ error }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export default {
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            errors: []
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/login', this.form, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                // Сохранение токена в localStorage
                localStorage.setItem('auth_token', response.data.access_token);

                alert('Login successful!');
                // Перенаправление на другую страницу или обновление
                this.$router.push('/');
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = Object.values(error.response.data.errors).flat();
                } else {
                    console.error('Login error:', error);
                    alert('Login failed.');
                }
            }
        }
    }
};
</script>

<style scoped>
/* Add some basic styling */
</style>
