<template>
    <nav class="navbar">
        <router-link to="/">Comments</router-link>
        <router-link to="/register">Register</router-link>
        <router-link to="/login">Login</router-link>
    </nav>
    <div class="max-w-md mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4">Register</h2>
        <form @submit.prevent="register" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                <input type="text" v-model="form.first_name" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                <input type="text" v-model="form.last_name" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" v-model="form.email" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" v-model="form.password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" v-model="form.password_confirmation" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Photo</label>
                <input type="file" @change="handleFileUpload"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Register
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '', // Add password confirmation field
                photo: null
            }
        };
    },
    methods: {
        handleFileUpload(event) {
            this.form.photo = event.target.files[0];
        },
        async register() {
            let formData = new FormData();
            formData.append('first_name', this.form.first_name);
            formData.append('last_name', this.form.last_name);
            formData.append('email', this.form.email);
            formData.append('password', this.form.password);
            formData.append('password_confirmation', this.form.password_confirmation); // Add password confirmation
            formData.append('photo', this.form.photo);

            try {
                await axios.post('/register', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                alert('Registration successful!');
            } catch (error) {
                console.error('Registration error:', error);
                alert('Registration failed.');
            }
        }
    }
};
</script>

<style scoped>
/* Add some basic styling */
</style>
