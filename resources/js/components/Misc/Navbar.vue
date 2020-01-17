<template>
    <div class="flex justify-between items-center bg-indigo-500 pl-4 h-16">
        <div class="flex-1 flex items-center">
            <p class="font-black text-white">Infinity121</p>
        </div>

        <div class="flex justify-end items-center">
            <router-link v-if="is_admin" to="/teachers" class="text-white px-4 mr-4 no-underline hover:underline">Teachers</router-link>
            <router-link v-if="is_admin" to="/subjects" class="text-white px-4 mr-4 no-underline hover:underline">Subjects</router-link>
            <router-link v-if="is_admin" to="/users" class="text-white px-4 mr-4 no-underline hover:underline">Users</router-link>
            <dropdown v-cloak
                      :name="username"
                      class="text-white h-12 flex items-center h-16 bg-indigo-600 px-4">
                <div slot="dropdown_content"
                     class="py-3">
                    <router-link to="/me/show"
                                 class="text-black no-underline hover:text-indigo-500 pb-3 block">My Profile
                    </router-link>
                    <router-link to="/me/user-info"
                                 class="text-black no-underline hover:text-indigo-500 pb-3 block">My User Info
                    </router-link>
                    <router-link to="/me/password/edit"
                                 class="text-black no-underline hover:text-indigo-500 pb-3 block">Reset Password</router-link>
                    <button @click="logout" type="button" class="text-black hover:text-indigo-500">Logout</button>
                </div>
            </dropdown>
        </div>
    </div>
</template>

<script type="text/babel">
    import {Dropdown} from "@dymantic/vuetilities";
    import {notify} from "../Messaging/notify";

    export default {

        components: {
            Dropdown,
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
                this.$store.dispatch('me/logout')
                    .then(() => window.location = '/')
                    .catch(notify.error);
            }
        }
    }
</script>
