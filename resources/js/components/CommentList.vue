<template>
    <div>
        <comment-form @commentAdded="addComment"></comment-form>
        <article v-for="comment in comments" :key="comment.id"
                 :class="['p-6', 'text-base', 'rounded-lg', { 'mb-3': comment.replies.length > 0, 'ml-6': comment.replies.length > 0, 'lg:ml-12': comment.replies.length > 0 }]">
            <div class="max-w-2xl mx-auto px-4">
                <footer class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                            <img class="mr-2 w-6 h-6 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" :alt="comment.user_name">
                            {{ comment.user_name }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <time :datetime="comment.created_at" :title="formattedDate">{{ formatDate(comment.created_at) }}</time>
                        </p>
                    </div>
                </footer>
                <p class="text-gray-500 dark:text-gray-400" v-html="sanitizeInput(comment.text)"></p>
                <div v-for="reply in comment.replies" :key="reply.id" class="p-6 mb-3 ml-6 lg:ml-12 text-base rounded-lg ">
                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                    <img class="mr-2 w-6 h-6 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" :alt="comment.user_name">
                    {{ reply.user_name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <time :datetime="reply.created_at" :title="formattedDate">{{ formatDate(reply.created_at) }}</time>
                    </p>
                    <p class="text-gray-500 dark:text-gray-400" v-html="sanitizeInput(reply.text)"></p>
                    <button type="button" @click="toggleReplies(comment.id)" class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium">
                        <svg class="mr-1.5 w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
                        </svg>
                        Reply
                    </button>
                </div>
                <div class="flex items-center mt-4 space-x-4">
                    <button type="button" @click="toggleReplies(comment.id)" class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium">
                        <svg class="mr-1.5 w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
                        </svg>
                        Reply
                    </button>
                    <div v-if="comment.showReplies" class="mt-4 ml-8">
                        <!-- Existing replies can be rendered here -->

                        <!-- Reply form -->
                        <form @submit.prevent="submitReply(comment.id)">
                            <div class="mb-4">
                                <input v-model="comment.replyForm.home_page" placeholder="Home Page" type="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                            </div>
                            <div class="py-2 px-4 mb-4 rounded-lg rounded-t-lg border border-gray-200 dark:border-gray-700">
                                <textarea v-model="comment.replyForm.text" class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800" placeholder="Write a reply..." required></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                    Post Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
        <!-- Pagination -->
        <pagination v-if="pagination" :data="pagination" @pagination-change-page="handlePageChange"></pagination>
    </div>
</template>

<script>
import axios from 'axios';
import CommentForm from './CommentForm.vue';
import Pagination from './Pagination.vue';
import DOMPurify from 'dompurify';
import { format } from 'date-fns';
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

const authToken = localStorage.getItem('auth_token');
if (authToken) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
}
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
            form: {
                text: ''
            },
            fetching: false // Request state variable
        };
    },
    async created() {
        this.getComments(); // Call the method to load comments once when the component is created
    },
    methods: {
        async getComments() {
            if (this.fetching) return; // Prevent duplicate requests if already fetching
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
                    showReplies: false, // Add a flag to manage replies visibility
                    replyForm: {
                        user_name: '',
                        email: '',
                        home_page: '',
                        text: ''
                    },
                    replies: comment.replies || [] // Assume replies data is returned in response
                })) || [];
                this.pagination = response.data.meta;
            } catch (error) {
                console.error('Error fetching comments:', error);
            } finally {
                this.fetching = false; // Reset state after the request completes
            }
        },
        toggleReplies(commentId) {
            const comment = this.comments.find(c => c.id === commentId);
            if (comment) {
                comment.showReplies = !comment.showReplies;
            }
        },
        sortBy(field) {
            if (this.fetching) return; // Prevent duplicate requests on sorting
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
            if (this.fetching) return; // Prevent duplicate requests on page change
            this.page = page;
            this.getComments();
        },
        formatDate(date) {
            return format(new Date(date), 'MMM. dd, HH:mm:ss');
        },
        sanitizeInput(text) {
            const allowedTags = ['a', 'code', 'i', 'strong'];
            const allowedAttributes = {
                'a': ['href', 'title']
            };
            return DOMPurify.sanitize(text, {
                ALLOWED_TAGS: allowedTags,
                ALLOWED_ATTR: allowedAttributes
            });
        },
        async submitReply(commentId) {
            const comment = this.comments.find(c => c.id === commentId);
            if (comment) {
                try {
                    const response = await axios.post(`/api/comments/${commentId}/replies`, comment.replyForm);
                    comment.replies.push(response.data);
                    comment.replyForm = {
                        user_name: '',
                        email: '',
                        home_page: '',
                        text: ''
                    };
                } catch (error) {
                    console.error('Error submitting reply:', error);
                }
            }
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
