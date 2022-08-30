/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



// require('./bootstrap');

window.Vue = require('vue').default;
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import router from './router';
require("./fontawesome");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('root-component', require('./components/RootComponent.vue').default);

// share
Vue.component('header-component', require('./components/share/HeaderComponent.vue').default);
Vue.component('footer-component', require('./components/share/FooterComponent.vue').default);
Vue.component('message-component', require('./components/share/MessageComponent.vue').default);
Vue.component('loading-component', require('./components/share/LoadingComponent.vue').default);
Vue.component('modal-component', require('./components/share/ModalComponent.vue').default);
Vue.component('modal-item-component', require('./components/share/modal/ItemComponent.vue').default);
Vue.component('modal-import-component', require('./components/share/modal/ImportComponent.vue').default);
Vue.component('modal-update-component', require('./components/share/modal/UpdateComponent.vue').default);
Vue.component('modal-alert-component', require('./components/share/modal/AlertComponent.vue').default);
Vue.component('modal-log-component', require('./components/share/modal/LogComponent.vue').default);
Vue.component('modal-login-component', require('./components/share/modal/LoginComponent.vue').default);
Vue.component('stock-input-component', require('./components/stock/step/InputComponent.vue').default);
Vue.component('stock-confirm-component', require('./components/stock/step/ConfirmComponent.vue').default);
Vue.component('stock-result-component', require('./components/stock/step/ResultComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: router,
    data: function(){
        return {
            session_time: 3600
        }
    }
});
