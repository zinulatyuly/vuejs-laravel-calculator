
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('lightning-protection', require('./components/LightningProtection.vue').default);

import VueMq from 'vue-mq';
Vue.use(VueMq, {
    breakpoints: {
        mobile: 768,
        desktop: Infinity,
    }
});

const app = new Vue({
    el: '#app'
});
