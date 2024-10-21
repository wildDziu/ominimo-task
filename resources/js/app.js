import '../css/app.css';
import './bootstrap';

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import AppLayout from './Layouts/AppLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        );
        page.then((module) => {
            module.default.layout = module.default.layout || AppLayout;
        });
        return page;
    },
    setup({el, App, props, plugin}) {
        const VueApp = createApp({render: () => h(App, props)});

        VueApp.config.errorHandler = (error) => {
            if (error.response && error.response.status === 404) {
                // Handle 404 errors
                console.error('404 Error:', error);
                // You could redirect to a custom 404 page here if you prefer
                // window.location.href = '/404';
            } else {
                // Handle other errors
                console.error('Unhandled error:', error);
            }
        };

        VueApp.use(plugin)
            .use(ZiggyVue)
            .mount(el);

        return VueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
