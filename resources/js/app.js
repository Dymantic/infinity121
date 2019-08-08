

require('./bootstrap');

import Vue from "vue";

import { Dropdown } from "@dymantic/vuetilities";
import Modal from "@dymantic/modal";
import {VueForm} from "@dymantic/vue-forms";

import NotificationHub from "./components/Messaging/NotificationHub";
import MultiButton from "./components/UI/MultiButton";

import UsersPage from "./components/Users/UsersPage";

Vue.component('dropdown-menu', Dropdown);
Vue.component('modal', Modal);
Vue.component('vue-form', VueForm);

Vue.component('multi-button', MultiButton);
Vue.component('notification-hub', NotificationHub);

Vue.component('users-page', UsersPage);

const app = new Vue({
    el: '#app',
});
