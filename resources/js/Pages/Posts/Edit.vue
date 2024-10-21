<template>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Post</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input v-model="form.title" id="title" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                <textarea v-model="form.content" id="content" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Post
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';

export default {
    props: {
        post: Object,
    },
    setup(props) {
        const form = useForm({
            title: props.post.title,
            content: props.post.content,
        });

        function submit() {
            form.put(route('posts.update', props.post.id));
        }

        return {form, submit};
    },
};
</script>
