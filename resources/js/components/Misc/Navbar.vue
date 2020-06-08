<template>
    <div class="flex justify-between items-center bg-hms-navy pl-4 h-16">
        <div class="flex-1 flex items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 196.6 23.65"
                class="h-4 text-mustard"
            >
                <path
                    d="M22.88 15.64c-3.42 3.16-6.64 6.71-12.29 6.71C4.27 22.35 0 17.61 0 11.37A10.82 10.82 0 0111.11.39C16 .39 19.73 4 23 7.09c3.37-3.15 6.59-6.7 12.24-6.7 6.32 0 10.59 4.73 10.59 11a10.83 10.83 0 01-11.11 11c-4.87-.04-8.62-3.66-11.84-6.75zm-4-4.21c-2-2-4.54-5-7.63-5a4.86 4.86 0 00-4.8 4.94 4.66 4.66 0 004.61 4.93c3.01 0 5.51-2.63 7.81-4.87zm20.52-.06a4.66 4.66 0 00-4.61-4.94c-3 0-5.52 2.63-7.82 4.87 2 2 4.54 5 7.63 5a4.86 4.86 0 004.79-4.93z"
                    class="fill-current"
                />
                <path
                    d="M58.29 23.65H46V20l2.94-1.12V4.74L46 3.64V0h12.29v3.64l-2.95 1.1v14.14L58.29 20zM83.16 23.65h-8.38L66.14 7H66c.06.77.11 1.47.14 2.09s.07 1.27.1 2 0 1.34 0 1.93v10.63h-5.6V0H69l8.6 16.43h.1c0-.75-.09-1.43-.12-2s-.06-1.23-.08-1.89 0-1.27 0-1.82V0h5.69zM91.91 23.65h-6.3V0h14v5.13h-7.7v4.51H99v5.13h-7.09zM114.36 23.65H102V20l3-1.12V4.74l-3-1.1V0h12.32v3.64l-3 1.1v14.14l3 1.12zM139.24 23.65h-8.38L122.21 7h-.13c.07.77.12 1.47.15 2.09s.06 1.27.1 2 0 1.34 0 1.93v10.63h-5.67V0h8.35l8.61 16.43h.1c0-.75-.08-1.43-.11-2s-.07-1.23-.09-1.89 0-1.27 0-1.82V0h5.7zM153.91 23.65h-12.32V20l2.95-1.12V4.74l-2.95-1.1V0h12.32v3.64L151 4.74v14.14l2.91 1.12zM168 23.65h-6.39V5.23h-5.77V0h17.92v5.23H168zM185.61 8.78L189.68 0h6.92l-7.78 14.41v9.24h-6.43v-9L174.61 0h7z"
                    fill="#fff"
                />
            </svg>
        </div>

        <div class="flex justify-end items-center">
            <!--            <dropdown-->
            <!--                v-cloak-->
            <!--                v-if="is_teacher"-->
            <!--                name="My Teaching"-->
            <!--                class="text-white font-bold hover:text-mustard h-12 flex items-center h-16 px-4"-->
            <!--            >-->
            <!--                <div slot="dropdown_content" class="py-3">-->
            <!--                    <router-link-->
            <!--                        to="/me/working-areas/edit"-->
            <!--                        class="text-hms-navy hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"-->
            <!--                        >Working Locations</router-link-->
            <!--                    >-->
            <!--                    <router-link-->
            <!--                        to="/me/available-periods"-->
            <!--                        class="text-hms-navy hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"-->
            <!--                        >Available Hours</router-link-->
            <!--                    >-->
            <!--                    <router-link-->
            <!--                        to="/me/unavailable-periods"-->
            <!--                        class="text-hms-navy hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"-->
            <!--                        >Unavailable Times</router-link-->
            <!--                    >-->
            <!--                </div>-->
            <!--            </dropdown>-->
            <!--            <router-link-->
            <!--                v-if="is_admin"-->
            <!--                to="/customers"-->
            <!--                class="text-white hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"-->
            <!--                >Customers</router-link-->
            <!--            >-->

            <router-link
                v-if="is_admin"
                to="/affiliates"
                class="text-white hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"
                >Affiliates</router-link
            >

            <!--            <router-link-->
            <!--                v-if="is_admin"-->
            <!--                to="/locations"-->
            <!--                class="text-white hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"-->
            <!--                >Locations</router-link-->
            <!--            >-->

            <router-link
                v-if="is_admin"
                to="/subjects"
                class="text-white hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"
                >Subjects</router-link
            >

            <dropdown
                v-cloak
                v-if="is_admin"
                name="People"
                class="text-white font-bold hover:text-mustard h-12 flex items-center h-16 px-4"
            >
                <div slot="dropdown_content" class="py-3">
                    <router-link
                        to="/teachers"
                        class="text-hms-navy hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"
                        >Teachers</router-link
                    >
                    <router-link
                        to="/users"
                        class="text-hms-navy hover:text-mustard font-bold px-4 mr-4 no-underline hover:underline"
                        >Users</router-link
                    >
                </div>
            </dropdown>
            <dropdown
                v-cloak
                :name="username"
                class="text-white hover:text-mustard h-12 flex items-center h-16 bg-deep-navy px-4"
            >
                <div slot="dropdown_content" class="py-3">
                    <router-link
                        to="/me/show"
                        class="text-black no-underline hover:text-mustard pb-3 block"
                        >My Profile
                    </router-link>
                    <router-link
                        to="/me/user-info"
                        class="text-black no-underline hover:text-mustard pb-3 block"
                        >My User Info
                    </router-link>
                    <router-link
                        to="/me/password/edit"
                        class="text-black no-underline hover:text-mustard pb-3 block"
                        >Reset Password</router-link
                    >
                    <button
                        @click="logout"
                        type="button"
                        class="text-black hover:text-mustard"
                    >
                        Logout
                    </button>
                </div>
            </dropdown>
        </div>
    </div>
</template>

<script type="text/babel">
import { Dropdown } from "@dymantic/vuetilities";
import { notify } from "../Messaging/notify";

export default {
    components: {
        Dropdown
    },

    computed: {
        username() {
            return this.$store.state.me.name;
        },

        is_teacher() {
            return this.$store.state.me.is_teacher;
        },

        is_admin() {
            return this.$store.state.me.is_admin;
        }
    },

    methods: {
        logout() {
            this.$store
                .dispatch("me/logout")
                .then(() => (window.location = "/"))
                .catch(notify.error);
        }
    }
};
</script>
