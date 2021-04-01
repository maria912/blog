
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';
import Vue from 'vue';
import Gate from './Gate';
Vue.prototype.$gate= new Gate(window.user);


import Swal from 'sweetalert2';

// CommonJS
window.Swal = Swal;
const toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
window.toast = toast;
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError) //register something globally

Vue.component('pagination', require('laravel-vue-pagination'));

import VueRouter from 'vue-router'
Vue.use(VueRouter)
import VueProgressBar from 'vue-progressbar'
import vueProgressbar from 'vue-progressbar';
Vue.use(VueProgressBar,{
    color:'rgb(143, 255, 199)',
   failedcolor: 'red',
    height: '3px'
    
    })

let routes = [
    { path: '/Dashboard', component: require('./components/Dashboard.vue') },
    { path: '/Developer', component: require('./components/Developer.vue') },
    { path: '/Users', component: require('./components/Users.vue') },
    { path: '/Profile', component: require('./components/Profile.vue') },
    { path: '*', component: require('./components/Notfound.vue') }
    // * mean any path
  ]
  const router = new VueRouter({
    mode: 'history',
  routes // short for `routes: routes`
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.filter('uptext', function(Text){
    return Text.charAt(0).toUpperCase() + Text.slice(1)

  });
  Vue.filter('myDate', function(created){
    return moment(created).format('MMMM Do YYYY');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 **/ 
  });
  Vue.component(
    'passport-Clients',
    require('./components/passport/Clients.vue')
   );
   
   Vue.component(
      'passport-Authorized-Clients',
      require('./components/passport/AuthorizedClients.vue')
    );
   
    Vue.component(
     'passport-Personal-Access-Tokens',
     require('./components/passport/PersonalAccessTokens.vue')
   );
   Vue.component(
    'not-found',
    require('./components/Notfound.vue')
  );
  Vue.component('example-component', require('./components/ExampleComponent.vue'));
  window.Fire = new Vue();
const app = new Vue({
    el: '#app',
    router,
    data:{
      search: ''
    },
    methods:{
      //debounce call a function after a few minute (minute ana ba7adedha)
      searchit: _.debounce(()=> {
        //console.log("searching. .");
        Fire.$emit('searching'); // create a cutom event
      }, 1000 ), //second parameter // when call searchit function wait two second and then will call the function
      printme(){
        window.print();
      } //when someone call this function print the window or the document we have
    }
});
