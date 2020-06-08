require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";

Vue.use(VueRouter);
Vue.use(Vuex);

import subjects from "./stores/subjects";
import users from "./stores/users";
import me from "./stores/me";
import teachers from "./stores/teachers";
import affiliates from "./stores/affiliates";
import locations from "./stores/locations";
import customers from "./stores/customers";

const store = new Vuex.Store({
    modules: {
        subjects,
        users,
        me,
        teachers,
        affiliates,
        locations,
        customers
    }
});

import profileRoutes from "./routes/profile";
import userRoutes from "./routes/users";
import subjectRoutes from "./routes/subjects";
import teacherRoutes from "./routes/teachers";
import affiliateRoutes from "./routes/affiliates";
import locationRoutes from "./routes/locations";
import customerRoutes from "./routes/customers";
import courseRoutes from "./routes/courses";

const routes = [
    ...profileRoutes,
    ...userRoutes,
    ...subjectRoutes,
    ...teacherRoutes,
    ...affiliateRoutes,
    ...locationRoutes,
    ...customerRoutes,
    ...courseRoutes
];
const router = new VueRouter({ routes });

import Navbar from "./components/Misc/Navbar";
import Modal from "@dymantic/modal";
import { VueForm } from "@dymantic/vue-forms";

import NotificationHub from "./components/Messaging/NotificationHub";

import UsersPage from "./components/Users/UsersPage";
import ProfilePage from "./components/Profiles/MainPage";
import SubjectsPage from "./components/Subjects/MainPage";

Vue.component("modal", Modal);
Vue.component("vue-form", VueForm);

Vue.component("users-page", UsersPage);
Vue.component("profile-page", ProfilePage);
Vue.component("subjects-page", SubjectsPage);

Vue.config.ignoredElements = ["trix-editor"];

const app = new Vue({
    components: {
        Navbar,
        NotificationHub
    },
    el: "#app",
    store,
    router,

    mounted() {
        this.$store.dispatch("me/hydrateFromPage");
        this.$store.dispatch("me/fetchProfile");
        this.$store.dispatch("me/fetchNationalities");
        this.$store.dispatch("subjects/fetchSubjects");
        this.$store.dispatch("teachers/fetchTeachers");
        this.$store.dispatch("affiliates/fetchAffiliates");
    }
});
