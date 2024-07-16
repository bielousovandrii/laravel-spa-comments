<template>
    <form @submit.prevent="submitComment">
        <input v-model="form.user_name" placeholder="User Name" required />
        <input v-model="form.email" placeholder="Email" type="email" required />
        <input v-model="form.home_page" placeholder="Home Page" type="url" />
        <input v-model="form.captcha" placeholder="CAPTCHA" required />
        <textarea v-model="form.text" placeholder="Comment" required></textarea>
        <button type="submit">Submit</button>
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
            }
        };
    },
    methods: {
        submitComment() {
            axios.post('/api/comments', this.form).then(response => {
                this.$emit('commentAdded', response.data);
                this.resetForm();
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
