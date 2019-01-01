
require('./bootstrap');

window.Vue = require('vue');

/**
 * Vue Globally Registered Components
 */

Vue.component('master-navigation', require('./components/MasterNavigationComponent.vue').default);
Vue.component('master-header', require('./components/MasterHeaderComponent.vue').default);
Vue.component('master-footer', require('./components/MasterFooterComponent.vue').default);
Vue.component('authentication-component', require('./components/auth/AuthenticationComponent.vue').default);

/**
 * Vue-Routers
 */

import VueRouter from 'vue-router';
Vue.use(VueRouter);

const routes = [
     { path: '/test', component: require('./components/ExampleComponent.vue').default},
];

let router = new VueRouter({
    mode: 'history',
    routes
});

/**
 * Vform
 */

import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

/**
 * Vue-Istance
 */

const app = new Vue({
    el: '#app',
    router
});


