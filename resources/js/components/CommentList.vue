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
                    <!-- Lazy load replies only when expanded -->
                    <button @click="toggleReplies(comment.id)">Toggle Replies</button>
                    <comment-list v-if="comment.showReplies && comment.replies" :comments="comment.replies"></comment-list>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <pagination v-if="pagination" :data="pagination" @pagination-change-page="handlePageChange"></pagination>
    </div>
</template>

<script>
import axios from 'axios';
import CommentForm from './CommentForm.vue';
import Pagination from './Pagination.vue';

export default {
    components: {
        CommentForm,
        Pagination
    },
    data() {
        return {
            comments: [],
            pagination: null,
            sortByField: 'created_at',
            sortOrder: 'desc',
            page: 1,
            fetching: false // Переменная состояния запроса
        };
    },
    async created() {
        this.getComments(); // Вызов метода загрузки комментариев один раз при создании компонента
    },
    methods: {
        async getComments() {
            if (this.fetching) return; // Предотвращаем повторные запросы, если уже идет загрузка
            this.fetching = true;
            try {
                const response = await axios.get('/api/comments', {
                    params: {
                        page: this.page,
                        sortBy: this.sortByField,
                        order: this.sortOrder
                    }
                });
                this.comments = response.data.data.map(comment => ({
                    ...comment,
                    showReplies: false // Добавляем флаг для управления отображением ответов
                })) || [];
                this.pagination = response.data.meta;
            } catch (error) {
                console.error('Error fetching comments:', error);
            } finally {
                this.fetching = false; // Сбрасываем состояние после завершения запроса
            }
        },
        toggleReplies(commentId) {
            const comment = this.comments.find(c => c.id === commentId);
            if (comment) {
                comment.showReplies = !comment.showReplies;
            }
        },
        sortBy(field) {
            if (this.fetching) return; // Предотвращаем повторные запросы при сортировке
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            this.sortByField = field;
            this.getComments();
        },
        addComment(comment) {
            this.comments.unshift(comment);
            if (this.comments.length > 25) {
                this.comments.pop();
            }
        },
        handlePageChange(page) {
            if (this.fetching) return; // Предотвращаем повторные запросы при изменении страницы
            this.page = page;
            this.getComments();
        }
    }
};
</script>

<style scoped>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

button {
    margin: 0 0.25rem;
}

button.active {
    font-weight: bold;
}
</style>
