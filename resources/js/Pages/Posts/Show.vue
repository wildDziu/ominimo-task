<template>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>
        <p class="text-gray-600 mb-2">By {{ post.user.name }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ getDateLabel(post.created_at, post.updated_at) }}</p>
        <div class="mb-8">{{ post.content }}</div>

        <div v-if="canEditOrDeletePost" class="mb-4">
            <Link :href="route('posts.edit', post.id)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                Edit
            </Link>
            <button @click="deletePost" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete
            </button>
        </div>

        <h2 class="text-2xl font-bold mb-4">Comments</h2>
        <div v-for="comment in post.comments" :key="comment.id" class="mb-4 p-4 border rounded">
            <p>{{ comment.comment }}</p>
            <p class="text-gray-600">
                By
                <span :class="{ 'text-blue-500': !comment.user }">
                    {{ comment.user ? comment.user.name : 'guest' }}
                </span>
            </p>
            <p class="text-xs text-gray-500">{{ getDateLabel(comment.created_at, comment.updated_at) }}</p>
            <button v-if="canDeleteComment(comment)" @click="deleteComment(comment.id)" class="text-red-500 hover:underline mt-2">
                Delete
            </button>
        </div>

        <form @submit.prevent="submitComment" class="mt-4">
            <textarea v-model="commentForm.comment" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Write a comment..."></textarea>
            <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Post Comment
            </button>
        </form>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => page.props.auth.isAdmin);

const canEditOrDeletePost = computed(() => {
    if (!user.value) return false;
    return user.value.id === props.post.user_id || isAdmin.value;
});

const commentForm = useForm({
    comment: '',
    user_id: null,
});

const deletePostForm = useForm({});
const deleteCommentForm = useForm({});

function submitComment() {
    commentForm.user_id = user.value ? user.value.id : null;
    commentForm.post(route('comments.store', props.post.id), {
        preserveScroll: true,
        resetOnSuccess: true,
    });
}

function deletePost() {
    if (confirm('Are you sure you want to delete this post?')) {
        deletePostForm.delete(route('posts.destroy', props.post.id));
    }
}

function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        deleteCommentForm.delete(route('comments.destroy', commentId), {
            preserveScroll: true,
        });
    }
}

function canDeleteComment(comment) {
    return user.value && (user.value.id === comment.user_id || user.value.id === props.post.user_id || isAdmin.value);
}

function getDateLabel(createdAt, updatedAt) {
    const created = new Date(createdAt);
    const updated = new Date(updatedAt);

    if (updated > created) {
        return `Modified at ${formatDate(updated)}`;
    } else {
        return `Created at ${formatDate(created)}`;
    }
}

function formatDate(date) {
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>
