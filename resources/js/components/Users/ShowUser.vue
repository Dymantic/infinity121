<template>
    <page v-if="user">
        <page-header :title="user.name">
            <delete-user :user="user"></delete-user>
        </page-header>

        <section class="my-16 mx-auto max-w-4xl">
            <h2 class="font-bold text-3xl">User Details</h2>
            <div class="p-4 bg-gray-100 shadow flex justify-between mt-8">
                <div class="">
                    <p class="form-label">Name</p>
                    <p>{{ user.name }}</p>
                </div>
                <div class="">
                    <p class="form-label">Email</p>
                    <p>{{ user.email }}</p>
                </div>
                <div class="">
                    <p class="form-label">Roles</p>

                    <p>{{ user_roles }}</p>
                </div>
            </div>
        </section>

        <admin-email-subscribe
            :user="user"
            v-if="user.is_admin"
        ></admin-email-subscribe>

        <section v-if="is_teacher" class="my-16 mx-auto max-w-4xl">
            <profile-card :profile="user.profile"></profile-card>
        </section>
    </page>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";
import ProfileCard from "../Profiles/ProfileCard";
import DeleteUser from "./DeleteUser";
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import AdminEmailSubscribe from "./AdminEmailSubscribe";

export default {
    components: {
        ProfileCard,
        DeleteUser,
        Page,
        PageHeader,
        AdminEmailSubscribe
    },

    computed: {
        user_id() {
            return this.$route.params.id;
        },

        user() {
            return this.$store.getters["users/byId"](this.user_id);
        },

        user_roles() {
            let roles = "";
            if (this.user.is_admin) {
                roles = roles + "Admin";
            }

            if (this.user.is_teacher) {
                roles = roles + " Teacher";
            }
            return roles;
        },

        is_teacher() {
            return this.user.is_teacher;
        }
    },

    mounted() {
        if (!this.user) {
            console.log("getting users");
            this.$store.dispatch("users/fetchAllUsers").catch(notify.error);
        }
    }
};
</script>
