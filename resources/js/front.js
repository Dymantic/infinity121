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

import Usher from "./libs/Usher";


import Flickity from "flickity";
window.Flickity = Flickity;

import jump from "jump.js";

import Modal from "@dymantic/modal";

import StudentSignup from "./front/Components/StudentSignup";
import TeacherSignup from "./front/Components/TeacherSignup";
import ContactForm from "./front/Components/ContactForm";

Vue.component('student-signup', StudentSignup);
Vue.component('teacher-signup', TeacherSignup);
Vue.component('contact-form', ContactForm);
Vue.component('modal', Modal);
new Vue({el: "#app"});
window.addEventListener('load', () => {
    initNavbar();
    const usher = new Usher();

    document.querySelectorAll('[data-jump]').forEach(link => {

        link.addEventListener('click', (ev) => {
            ev.preventDefault();
            const target = link.getAttribute('data-jump-target') || 'body';
            const offset = link.getAttribute('data-jump-offset') || 0;

            jump(target, {offset});
        });
    })
});


