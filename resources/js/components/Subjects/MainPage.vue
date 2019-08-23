<template>
    <div class="max-w-4xl mx-auto">
        <router-view></router-view>
    </div>

</template>

<script type="text/babel">
    import VueRouter from "vue-router";
    import Edit from "./Edit";
    import Index from "./Index";
    import Show from "./Show";
    import {notify} from "../Messaging/notify";

    const routes = [
        {path: "/", component: Index},
        {path: "/show/:id", component: Show},
        {path: "/edit/:id", component: Edit},
    ];

    const router = new VueRouter({routes});

    export default {
        router,

        mounted() {
            this.$store.dispatch('subjects/fetchSubjects')
                .catch(notify.error);
        }
    }
</script>

