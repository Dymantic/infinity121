require('./bootstrap');

import Vue from "vue";

import { Dropdown } from "@dymantic/vuetilities";

Vue.component('dropdown-menu', Dropdown);

const app = new Vue({
    el: '#app',
});
