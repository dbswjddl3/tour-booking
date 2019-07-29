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
    { name: 'booking', path: '/booking/:id', component: require('./components/Booking.vue').default },
];

const router = new VueRouter({routes: routes});

const app = new Vue({
    router
}).$mount('#app')
