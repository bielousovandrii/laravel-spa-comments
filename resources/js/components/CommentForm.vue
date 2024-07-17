<template>
    <nav class="navbar">
        <router-link to="/">Comments</router-link>
        <router-link to="/register">Register</router-link>
        <router-link to="/login">Login</router-link>
    </nav>
        <form @submit.prevent="submitComment" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <input v-model="form.user_name" placeholder="User Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required />
            </div>
            <div class="mb-4">
                <input v-model="form.email" placeholder="Email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required />
            </div>
            <div class="mb-4">
                <input v-model="form.home_page" placeholder="Home Page" type="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4 flex items-center">
                <img :src="captchaImage" @click="refreshCaptcha" class="cursor-pointer">
                <input v-model="form.captcha" placeholder="CAPTCHA" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ml-2" required />
            </div>
            <div class="mb-6">
                <textarea v-model="form.text" placeholder="Comment" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-none" required></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
            </div>
        </form>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                user_name: '',
                email: '',
                home_page: '',
                captcha: '',
                text: ''
            },
            captchaImage: ''
        };
    },
    created() {
        this.refreshCaptcha();
    },
    methods: {
        refreshCaptcha() {
            axios.get('/captcha').then(response => {
                this.captchaImage = response.data.captcha_src;
            });
        },
        submitComment() {
            axios.post('/api/comments', this.form).then(response => {
                this.$emit('commentAdded', response.data);
                this.resetForm();
                this.refreshCaptcha();
            });
        },
        resetForm() {
            this.form = {
                user_name: '',
                email: '',
                home_page: '',
                captcha: '',
                text: ''
            };
        }
    }
};
</script>
