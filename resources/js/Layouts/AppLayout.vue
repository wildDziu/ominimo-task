<template>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <button v-if="showBackButton" @click="goBack"
                                class="mr-4 p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-900">{{ appName }}</h1>
                    </div>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-bold text-gray-900">Welcome to Our Blog</h1>
                    </div>
                    <div v-if="$page.props.auth.user" class="flex items-center">
                        <span class="mr-4 text-sm font-medium text-gray-700">
                            {{ $page.props.auth.user.name }}
                        </span>
                        <Link :href="route('logout')" method="post" as="button"
                              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </Link>
                    </div>
                    <div v-else class="flex items-center space-x-4">
                        <Link :href="route('login')"
                              class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                            Login
                        </Link>
                        <Link :href="route('register')"
                              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <slot></slot>
        </main>

        <footer class="bg-white shadow-md mt-8">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                © {{ new Date().getFullYear() }} {{ appName }}. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<script setup>
import {Link, router} from '@inertiajs/vue3';
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

const page = usePage();
const appName = computed(() => page.props.app.name);

const showBackButton = computed(() => {
    const currentRoute = page.url;
    return !['/', '/dashboard'].includes(currentRoute);
});

function goBack() {
    const previousPage = document.referrer;
    if (previousPage) {
        router.visit(previousPage, {
            preserveState: false,
            preserveScroll: false,
            replace: true
        });
    } else {
        router.visit('/');
    }
}
</script>
