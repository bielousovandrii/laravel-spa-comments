<template>
    <div>
        <!-- Comment Form -->
        <comment-form @commentAdded="addComment"></comment-form>
        
        <!-- Comment List Table -->
        <table>
            <thead>
                <tr>
                    <th @click="sortBy('user_name')">User Name</th>
                    <th @click="sortBy('email')">Email</th>
                    <th @click="sortBy('created_at')">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="comment in comments" :key="comment.id">
                    <td>{{ comment.user_name }}</td>
                    <td>{{ comment.email }}</td>
                    <td>{{ comment.created_at }}</td>
                    <td>
                        <comment-list :comments="comment.replies"></comment-list>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <pagination :data="pagination" @pagination-change-page="getComments"></pagination>
    </div>
</template>

<script>
import axios from 'axios';
import CommentForm from './CommentForm.vue';

export default {
    components: {
        CommentForm
    },
    data() {
        return {
            comments: [],
            pagination: {},
            sortByField: 'created_at',
            sortOrder: 'desc'
        };
    },
    mounted() {
        this.getComments();
    },
    methods: {
        getComments(page = 1) {
            axios.get(`/api/comments?page=${page}&sortBy=${this.sortByField}&order=${this.sortOrder}`).then(response => {
                this.comments = response.data.data;
                this.pagination = response.data.meta;
            });
        },
        sortBy(field) {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            this.sortByField = field;
            this.getComments();
        },
        addComment(comment) {
            this.comments.unshift(comment);
            if (this.comments.length > 25) {
                this.comments.pop();
            }
        }
    }
};
</script>
