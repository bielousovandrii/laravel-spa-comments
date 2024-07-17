<template>
    <div>
        <form @submit.prevent="register">
            <div>
                <label for="first_name">First Name</label>
                <input type="text" v-model="form.first_name" required>
            </div>
            <div>
                <label for="last_name">Last Name</label>
                <input type="text" v-model="form.last_name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" v-model="form.email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" v-model="form.password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" v-model="form.password_confirmation" required>
            </div>
            <div>
                <label for="photo">Photo</label>
                <input type="file" @change="handleFileUpload">
            </div>
            <button type="submit">Register</button>
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
