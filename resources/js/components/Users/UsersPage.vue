<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Users</h1>
            <div class="flex justify-end items-center">
                <multi-button class="btn btn-indigo"
                              text="Add User">
                    <span class="text-black hover:text-indigo-500"
                          @click="showAdminForm = true">Admin</span>
                    <span class="text-black hover:text-indigo-500"
                          @click="showTeacherForm = true">Teacher</span>
                </multi-button>
            </div>
        </section>
        <section class="my-12">
            <user-list :users="admin_users" title="Admin"></user-list>

        </section>
        <section class="my-12">
            <user-list :users="teachers" title="Teachers"></user-list>

        </section>
        <add-admin :show-form="showAdminForm"
                   @close="showAdminForm = false"
                   @failed-add-admin="addAdminError"
                   @added-admin-user="adminUserAdded"></add-admin>
        <add-teacher :show-form="showTeacherForm"
                     @added-teacher-user="teacherUserAdded"
                     @failed-add-teacher="addTeacherError"
                     @close="showTeacherForm = false"></add-teacher>
    </div>
</template>

<script type="text/babel">
    import UserList from "./UserList";
    import AddAdmin from "./AddAdmin";
    import AddTeacher from "./AddTeacher";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            AddAdmin,
            AddTeacher,
            UserList,
        },

        data() {
            return {
                showAdminForm: false,
                showTeacherForm: false,
                users: [],
            };
        },

        computed: {
            admin_users() {
                return this.users.filter(f => f.is_admin);
            },

            teachers() {
                return this.users.filter(f => f.is_teacher && !f.is_admin);
            }

        },

        mounted() {
            this.refreshUserList();
        },

        methods: {

            fetchUsers() {
                return new Promise((resolve, reject) => {
                    axios.get("/admin/users")
                         .then(({data}) => {
                             this.users = data;
                             resolve();
                         })
                         .catch(() => resolve({message: "Failed to fetch list of users."}));
                });
            },

            refreshUserList() {
                this.fetchUsers().catch(notify.error);
            },

            adminUserAdded() {
                notify.success({message: "The admin user has been added and will be notified."})
            },

            teacherUserAdded() {
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

<style scoped
       lang="scss"
       type="text/scss">

</style>