import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy';
import { InertiaProgress } from '@inertiajs/progress';
import PortalVue from 'portal-vue';

InertiaProgress.init({
    delay: 0,
    color: '#4caf50',
    includeCSS: true,
    showSpinner: false,
});

createInertiaApp({
    title: (title) => (title ? `${title} — День Земли` : 'День Земли — каждый день!'),
    resolve: (name) => {
        const page = require(`./Pages/${name}.vue`).default;
        page.layout ??= require('./Layouts/AppLayout.vue').default;
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PortalVue)
            .component('InertiaLink', Link)
            .component('InertiaHead', Head)
            .mount(el);
    },
});
