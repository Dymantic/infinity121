<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Users</h1>
            <div class="flex justify-end items-center">
                <multi-button class="btn btn-navy"
                              text="Add User">
                    <span class="text-black hover:text-hms-navy"
                          @click="showAdminForm = true">Admin</span>
                    <span class="text-black hover:text-hms-navy"
                          @click="showTeacherForm = true">Teacher</span>
                </multi-button>
            </div>
        </section>
        <section class="my-12">
            <user-list :users="users" title="Users"></user-list>

        </section>

        <add-admin :show-form="showAdminForm"
                   @close="showAdminForm = false"
                   @failed-add-admin="addAdminError"
                   @added-admin-user="adminUserAdded"/>
        <add-teacher :show-form="showTeacherForm"
                     @added-teacher-user="teacherUserAdded"
                     @failed-add-teacher="addTeacherError"
                     @close="showTeacherForm = false"/>
    </div>
</template>

<script type="text/babel">
    import UserList from "./UserList";
    import AddAdmin from "./AddAdmin";
    import AddTeacher from "./AddTeacher";
    import {notify} from "../Messaging/notify";
    import MultiButton from "../UI/MultiButton";


    export default {
        components: {
            AddAdmin,
            AddTeacher,
            UserList,
            MultiButton,
        },

        data() {
            return {
                showAdminForm: false,
                showTeacherForm: false,
            };
        },

        computed: {
            admin_users() {
                return this.$store.getters['users/admins'];
            },

            teachers() {
                return this.$store.getters['users/teachers'];
            },

            users() {
                return this.$store.state.users.users;
            }

        },

        mounted() {
            this.$store.dispatch('users/fetchAllUsers').catch(notify.error);
        },

        methods: {

            adminUserAdded() {
                this.$store.dispatch('users/fetchAllUsers').catch(notify.error);
                notify.success({message: "The admin user has been added and will be notified."})
            },

            teacherUserAdded() {
                this.$store.dispatch('users/fetchAllUsers').catch(notify.error);
                notify.success({message: "The teacher has been added and will be notified."})
            },

            addAdminError() {
                notify.error({message: 'There was a problem adding that user.'});
            },

            addTeacherError() {
                notify.error({message: 'There was a problem adding that teacher.'});
            }

        }
    }
</script>
