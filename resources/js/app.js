

require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";

Vue.use(VueRouter);
Vue.use(Vuex);

import profiles from "./stores/profiles";
import subjects from "./stores/subjects";

const store = new Vuex.Store({
    modules: {
        profiles,
        subjects,
    }
});

import { Dropdown } from "@dymantic/vuetilities";
import Modal from "@dymantic/modal";
import {VueForm} from "@dymantic/vue-forms";

import NotificationHub from "./components/Messaging/NotificationHub";
import MultiButton from "./components/UI/MultiButton";

import UsersPage from "./components/Users/UsersPage";
import ProfilePage from "./components/Profiles/MainPage";
import SubjectsPage from "./components/Subjects/MainPage";

Vue.component('dropdown-menu', Dropdown);
Vue.component('modal', Modal);
Vue.component('vue-form', VueForm);

Vue.component('multi-button', MultiButton);
Vue.component('notification-hub', NotificationHub);

Vue.component('users-page', UsersPage);
Vue.component('profile-page', ProfilePage);
Vue.component('subjects-page', SubjectsPage);

Vue.config.ignoredElements = [
    'trix-editor',
];

const app = new Vue({
    store,
    el: '#app',
});
