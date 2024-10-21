<template>
    <div class="min-h-screen bg-gray-100">
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Recent Posts</h2>
                            <ul class="space-y-4">
                                <li v-for="post in recentPosts" :key="post.id" class="border p-4 rounded-lg">
                                    <h3 class="text-lg font-medium">{{ post.title }}</h3>
                                    <p class="text-sm text-gray-500">By {{ post.user.name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ getDateLabel(post.created_at, post.updated_at) }}
                                    </p>
                                    <Link :href="route('posts.show', post.id)"
                                          class="text-indigo-600 hover:text-indigo-800">Read more
                                    </Link>
                                    <div v-if="isLoggedIn && post.user_id === currentUserId" class="mt-2">
                                        <Link :href="route('posts.edit', post.id)"
                                              class="text-sm text-blue-600 hover:text-blue-800 mr-2">Edit
                                        </Link>
                                        <button @click="deletePost(post.id)"
                                                class="text-sm text-red-600 hover:text-red-800">Delete
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            <Link :href="route('posts.index')"
                                  class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">View all posts
                            </Link>
                            <Link v-if="isLoggedIn" :href="route('posts.create')"
                                  class="ml-4 inline-block text-green-600 hover:text-green-800">Create new post
                            </Link>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Recent Comments</h2>
                            <ul class="space-y-4">
                                <li v-for="comment in recentComments" :key="comment.id" class="border p-4 rounded-lg">
                                    <p class="mb-2">{{ comment.comment }}</p>
                                    <p class="text-sm text-gray-500">
                                        By
                                        <span :class="{ 'text-blue-500': !comment.user }">
                                            {{ comment.user ? comment.user.name : 'guest' }}
                                        </span>
                                        on post: {{ comment.post.title }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ getDateLabel(comment.created_at, comment.updated_at) }}
                                    </p>
                                    <Link :href="route('posts.show', comment.post.id)"
                                          class="text-indigo-600 hover:text-indigo-800">View post
                                    </Link>
                                    <button
                                        v-if="isLoggedIn && (comment.user_id === currentUserId || comment.post.user_id === currentUserId)"
                                        @click="deleteComment(comment.id)"
                                        class="ml-2 text-sm text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3';

const props = defineProps({
    isLoggedIn: Boolean,
    currentUserId: Number,
    loginLink: String,
    registerLink: String,
    recentPosts: Array,
    recentComments: Array,
});

const form = useForm({});

const deletePost = (postId) => {
    if (confirm('Are you sure you want to delete this post?')) {
        form.delete(route('posts.destroy', postId));
    }
};

const deleteComment = (commentId) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        form.delete(route('comments.destroy', commentId));
    }
};

const getDateLabel = (createdAt, updatedAt) => {
    const created = new Date(createdAt);
    const updated = new Date(updatedAt);

    if (updated > created) {
        return `Modified at ${formatDate(updated)}`;
    } else {
        return `Created at ${formatDate(created)}`;
    }
};

const formatDate = (date) => {
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>
