
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
Vue.component('announcement-component', require('./components/content/AnnouncementsComponent.vue').default);
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
    { path: '/', component: require('./components/content/SlidersComponent.vue').default},
    { path: '/Новини', component: require('./components/content/NewsComponent.vue').default},
    { path: '/Треньори', component: require('./components/content/CoachsComponent.vue').default},
    { path: '/Състезатели/:page', component: require('./components/content/CompetitorsComponent.vue').default},
    { path: '/График', component: require('./components/content/SchedulesComponent.vue').default},
    { path: '/Галерии/:type', component: require('./components/content/GalleriesComponent.vue').default},
    { path: '/Галерии/:type/Галерия/:title/Id/:id', component: require('./components/content/GalleryComponent.vue').default},
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

/*
 * Vue - filters
*/
import moment from 'moment';
Vue.filter('capitalize', function (text) {
    if (!text) return ''
    text = text.toString()
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function (created) {
    created = new Date(created)
    return moment(created).format('MMM DD YYYY')
});

Vue.filter('getAge', function (birthday) {
    birthday = new Date(birthday)
    let today = new Date()
    let age = moment(today).format('YYYY') - moment(birthday).format('YYYY');
    return age
});



/**
 * Vue-Istance
 */

const app = new Vue({
    el: '#app',
    router
});


