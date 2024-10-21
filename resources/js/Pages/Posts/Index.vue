<template>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Blog Posts</h1>
        <Link v-if="$page.props.auth.user"
              :href="route('posts.create')"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Create New Post
        </Link>
        <div v-for="post in posts.data" :key="post.id" class="mb-4 p-4 border rounded">
            <h2 class="text-xl font-semibold">{{ post.title }}</h2>
            <p class="text-gray-600">By {{ post.user.name }}</p>
            <p class="mt-2">{{ post.content.substring(0, 100) }}...</p>
            <Link :href="route('posts.show', post.id)" class="text-blue-500 hover:underline">
                Read More
            </Link>
        </div>
        <div v-if="posts.links.length > 3" class="mt-6">
            <Pagination :links="posts.links"/>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

export default {
    components: {
        Link,
        Pagination,
    },
    props: {
        posts: Object,
    },
};
</script>
