<template>
    <div>
        <input
            v-model="searchQuery"
            @input="getComments"
            placeholder="Search comments..."
        />
        <div v-if="filteredComments.length">
            <article
                v-for="comment in filteredComments"
                :key="comment.id"
                :class="[
          'p-6',
          'text-base',
          'rounded-lg',
          {
            'mb-3': comment.replies.length > 0,
            'ml-6': comment.replies.length > 0,
            'lg:ml-12': comment.replies.length > 0,
          },
        ]"
            >
                <div class="max-w-2xl mx-auto px-4">
                    <footer class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <p
                                class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold"
                            >
                                <img
                                    class="mr-2 w-6 h-6 rounded-full"
                                    src="https://flowbite.com/docs/images/people/profile-picture-2.jpg"
                                    :alt="comment.user_name"
                                />
                                {{ comment.user_name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <time
                                    :datetime="comment.created_at"
                                    :title="formattedDate"
                                >{{ formatDate(comment.created_at) }}</time
                                >
                            </p>
                        </div>
                    </footer>
                    <p
                        class="text-gray-500 dark:text-gray-400"
                        v-html="sanitizeInput(comment.text)"
                    ></p>
                    <div
                        v-for="reply in comment.replies"
                        :key="reply.id"
                        class="p-6 mb-3 ml-6 lg:ml-12 text-base rounded-lg"
                    >
                        <p
                            class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold"
                        >
                            <img
                                class="mr-2 w-6 h-6 rounded-full"
                                src="https://flowbite.com/docs/images/people/profile-picture-2.jpg"
                                :alt="comment.user_name"
                            />
                            {{ reply.user_name }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <time
                                :datetime="reply.created_at"
                                :title="formattedDate"
                            >{{ formatDate(reply.created_at) }}</time
                            >
                        </p>
                        <p
                            class="text-gray-500 dark:text-gray-400"
                            v-html="sanitizeInput(reply.text)"
                        ></p>
                        <button
                            type="button"
                            @click="toggleReplies(comment.id)"
                            class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium"
                        >
                            <svg
                                class="mr-1.5 w-3.5 h-3.5"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 18"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"
                                />
                            </svg>
                            Reply
                        </button>
                    </div>
                    <div class="flex items-center mt-4 space-x-4">
                        <button
                            type="button"
                            @click="toggleReplies(comment.id)"
                            class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium"
                        >
                            <svg
                                class="mr-1.5 w-3.5 h-3.5"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 18"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2 7h16M2 11h16"
                                />
                            </svg>
                            Show Replies
                        </button>
                    </div>
                </div>
            </article>
        </div>
        <div v-else>
            <p>No comments found.</p>
        </div>
        <comment-form @commentAdded="addComment"></comment-form>
    </div>
</template>

<script>
import axios from 'axios';
import CommentForm from './CommentForm.vue';

export default {
    components: {
        CommentForm,
    },
    data() {
        return {
            searchQuery: '',
            comments: [],
            filteredComments: [],
            replyVisibility: {}, // Track visibility of replies
        };
    },
    methods: {
        async getComments() {
            try {
                const response = await axios.get('/api/comments', {
                    params: { search: this.searchQuery },
                });
                this.comments = response.data;
                this.filteredComments = this.comments;
            } catch (error) {
                console.error('Error fetching comments:', error);
            }
        },
        addComment(newComment) {
            this.comments.push(newComment);
            this.getComments(); // Refresh comments
        },
        sanitizeInput(input) {
            return input; // Add sanitization logic if needed
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString(); // Adjust format as needed
        },
        toggleReplies(commentId) {
            this.$set(this.replyVisibility, commentId, !this.replyVisibility[commentId]);
        },
    },
    watch: {
        searchQuery() {
            this.getComments();
        },
    },
    mounted() {
        this.getComments();
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>
