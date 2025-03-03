import '../css/app.css';
import './bootstrap';
import $ from "jquery";

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import DataTablesLib from 'datatables.net'; 
import DataTable from 'datatables.net-vue3';
 
DataTable.use(DataTablesLib);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        // Create the Vue app
        const vueApp = createApp({ render: () => h(App, props) });

        // Register the Inertia.js plugin
        vueApp.use(plugin);

        // Register the ZiggyVue plugin for route generation
        vueApp.use(ZiggyVue);

        // Register VueDatePicker globally
        vueApp.component('VueDatePicker', VueDatePicker);


        //Datatable use
        vueApp.component('DataTable', DataTable) 

        // Mount the app
        vueApp.mount(el);

        return vueApp; // Return the app instance if further extensions are needed
    },
    progress: {
        color: '#4B5563', // Configure progress bar color
    },
});


