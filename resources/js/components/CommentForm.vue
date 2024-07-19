<template>
<!--    nav in next update if we need tihs )-->
<!--    <nav class="navbar">-->
<!--        <router-link to="/">Comments</router-link>-->
<!--        <router-link to="/register">Register</router-link>-->
<!--        <router-link to="/login">Login</router-link>-->
<!--    </nav>-->
    <section class="py-8 lg:py-16 antialiased">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Add you're comment</h2>
            </div>
        <form @submit.prevent="submitComment" class="mb-6">
            <div class="mb-4">
                <input v-model="form.home_page" placeholder="Home Page" type="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4 flex items-center">
                <img :src="captchaImage" @click="refreshCaptcha" class="cursor-pointer">
                <input v-model="form.captcha" placeholder="CAPTCHA" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ml-2" required />
            </div>
            <div class="py-2 px-4 mb-4 rounded-lg rounded-t-lg border border-gray-200  dark:border-gray-700">
                <textarea v-model="form.text" @input="sanitizeInput" class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:placeholder-gray-400 dark:bg-gray-800"
                          placeholder="Write a comment..." required></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Post comment
                </button>
            </div>
        </form>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                home_page: '',
                captcha: '',
                text: ''
            },
            captchaImage: '',
            rawText: '',
            sanitizedText: ''
        };
    },
    created() {
        this.refreshCaptcha();
        this.setAxiosDefaults();
    },
    methods: {
        setAxiosDefaults() {
            // Установка CSRF токена для всех запросов
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

            // Установка токена авторизации, если он есть в хранилище
            const authToken = localStorage.getItem('auth_token');
            if (authToken) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
            }
        },
        refreshCaptcha() {
            axios.get('/api/captcha').then(response => {
                this.captchaImage = response.data.captcha_src;
            }).catch(error => {
                console.error('Error fetching captcha:', error);
            });
        },
        submitComment() {
            axios.post('/api/comments', this.form).then(response => {
                this.$emit('commentAdded', response.data);
                this.resetForm();
                this.refreshCaptcha();
            }).catch(error => {
                console.error('Error submitting comment:', error);
            });
        },
        resetForm() {
            this.form = {
                home_page: '',
                captcha: '',
                text: ''
            };
        },
        sanitizeInput() {
            const allowedTags = ['a', 'code', 'i', 'strong'];
            const allowedAttributes = {
                'a': ['href', 'title'],
            };

            this.sanitizedText = DOMPurify.sanitize(this.form.text, {
                ALLOWED_TAGS: allowedTags,
                ALLOWED_ATTR: allowedAttributes,
            });
        }
    }
};
</script>
<style>
.text-white {
    background: #123e9d;
    border: 1px solid #000;
    --tw-text-opacity: 1;
    color: rgb(255 255 255 / var(--tw-text-opacity));
}
</style>
