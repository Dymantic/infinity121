window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

import {initNavbar} from "./front/navbar";
import Vue from "vue";

import Modal from "@dymantic/modal";

import StudentSignup from "./front/Components/StudentSignup";
import TeacherSignup from "./front/Components/TeacherSignup";

Vue.component('student-signup', StudentSignup);
Vue.component('teacher-signup', TeacherSignup);
Vue.component('modal', Modal);
new Vue({el: "#app"});
window.addEventListener('load', () => {
    initNavbar();
});


