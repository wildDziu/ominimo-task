<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <h1 class="text-4xl font-bold mb-4">{{ title }}</h1>
        <p class="text-xl mb-4">{{ description }}</p>
        <Link :href="route('home')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Go back to home
        </Link>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    status: Number,
    title: String,
    description: String,
});
</script>
