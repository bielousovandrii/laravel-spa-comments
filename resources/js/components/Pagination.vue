<template>
    <div class="pagination" v-if="pagination">
        <button
            @click="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page <= 1"
        >
            Previous
        </button>

        <span v-for="page in pages" :key="page">
            <button
                @click="changePage(page)"
                :class="{ active: page === pagination.current_page }"
            >
                {{ page }}
            </button>
        </span>

        <button
            @click="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page >= pagination.last_page"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    computed: {
        pagination() {
            return this.data;
        },
        pages() {
            if (!this.pagination) return [];
            let start = Math.max(1, this.pagination.current_page - 2);
            let end = Math.min(this.pagination.last_page, this.pagination.current_page + 2);

            return Array.from({ length: end - start + 1 }, (_, i) => start + i);
        }
    },
    methods: {
        changePage(page) {
            if (page !== this.pagination.current_page) {
                console.log('Changing to page:', page);
                this.$emit('pagination-change-page', page);
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
