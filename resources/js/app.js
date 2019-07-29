require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
window.VueAxios = require('vue-axios').default;
window.Axios = require('axios').default;

Vue.use(VueRouter,VueAxios,axios);

const routes = [
    { name: 'tourList', path: '/', component: require('./components/TourList.vue').default },
    { name: 'tourAdd', path: '/tour-add', component: require('./components/TourAdd.vue').default },
    { name: 'tourEdit', path: '/tour-edit/:id', component: require('./components/TourEdit.vue').default },
    { name: 'bookingList', path: '/booking-list', component: require('./components/BookingList.vue').default },
    { name: 'bookingAdd', path: '/booking-add/:tourId', component: require('./components/BookingAdd.vue').default },
    { name: 'bookingEdit', path: '/booking-edit/:id', component: require('./components/BookingEdit.vue').default },
];

const router = new VueRouter({routes: routes});

const app = new Vue({
    router
}).$mount('#app')
