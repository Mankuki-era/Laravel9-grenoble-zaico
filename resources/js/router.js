import Vue from 'vue';
import VueRouter from 'vue-router';
import VueCookies from 'vue-cookies';

Vue.use(VueRouter);
Vue.use(VueCookies);

import ItemIndex from './components/item/IndexComponent.vue';
import Login from './components/auth/LoginComponent.vue';
import StockIndex from './components/stock/IndexComponent.vue';
import LogIndex from './components/log/IndexComponent.vue';
import AnalysisIndex from './components/analysis/IndexComponent.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter: (to, from, next) => {
      if(Vue.$cookies.isKey('auth')){
        Vue.$cookies.remove('auth');
      }
      next();
    }
  },
  {
    path: '/',
    name: 'item_index',
    component: ItemIndex,
    beforeEnter: (to, from, next) => {
      if(Vue.$cookies.isKey('auth')){
        next();
      }else{
        next({ name: 'login' });
      };
    }
  },
  {
    path: '/stock',
    name: 'stock_index',
    component: StockIndex,
    beforeEnter: (to, from, next) => {
      if(Vue.$cookies.isKey('auth')){
        next();
      }else{
        next({ name: 'login' });
      };
    }
  },
  {
    path: '/log',
    name: 'log_index',
    component: LogIndex,
    beforeEnter: (to, from, next) => {
      if(Vue.$cookies.isKey('auth')){
        next();
      }else{
        next({ name: 'login' });
      };
    }
  },
  {
    path: '/analysis',
    name: 'analysis_index',
    component: AnalysisIndex,
    beforeEnter: (to, from, next) => {
      if(Vue.$cookies.isKey('auth')){
        next();
      }else{
        next({ name: 'login' });
      };
    }
  },
];

const router = new VueRouter({
  mode: 'history',
  routes: routes
});

export default router