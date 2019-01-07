
require('./bootstrap');

window.Vue = require('vue');

/*
 * MyGate
*/
import Gate from './Gate';
if (window.user) {
Vue.prototype.$gate = new Gate(window.user);
} else {
    let user = {
        role: 'guest'
    }
    Vue.prototype.$gate = new Gate(user)
}



/**
 * Vue Globally Registered Components
 */
Vue.component('announcement-component', require('./components/content/news/AnnouncementComponent.vue').default);
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
     { path: '/', component: require('./components/content/slider/SlidersComponent.vue').default}
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



/*
 * sweetalert2
*/
import Swal from 'sweetalert2';
window.swal = Swal
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
window.toast = toast;



/**
 * Vue-Istance
 */

const app = new Vue({
    el: '#app',
    router
});

