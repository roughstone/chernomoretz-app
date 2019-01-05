
require('./bootstrap');

window.Vue = require('vue');

/*
 * MyGate
*/
import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);

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
     { path: '/', component: require('./components/content/slider/SlidersComponent.vue').default},
     { path: '/За-спорта/:id', name: 'За-спорта',  component: require('./components/content/slider/SliderComponent.vue').default },
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


