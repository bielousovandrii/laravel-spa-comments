<template>
    <div>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email</label>
                <input type="email" v-model="form.email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" v-model="form.password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div v-if="errors.length">
            <ul>
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

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
                await axios.post('/login', this.form);
                alert('Login successful!');
                // Redirect to another page or refresh
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
