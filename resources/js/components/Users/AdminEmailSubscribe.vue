<template>
    <div class="flex justify-between items-center shadow p-6 bg-gray-100">
        <div class="max-w-md" v-if="user.receive_admin_emails">
            <div class="flex items-center">
                <check-icon class="text-green-500 h-5 mr-3"></check-icon>
                <span>Subscribed to admin emails</span>
            </div>
            <p class="my-4 text-sm">
                This user will receive admin emails, such as when a student or
                teacher sign-up, or the contact form is completed.
            </p>
        </div>
        <div class="max-w-md" v-else>
            <div class="flex items-center">
                <mute-icon class="text-red-500 h-5 mr-3"></mute-icon>
                <span>Unsubscribed from admin emails</span>
            </div>
            <p class="my-4 text-sm">
                This user will not receive any admin emails, such as when a
                student or teacher sign-up, or the contact form is completed.
            </p>
        </div>
        <button
            :disabed="waiting"
            @click="toggle"
            class="btn"
            :class="{
                'btn-navy': !user.receive_admin_emails,
                'bg-white': user.receive_admin_emails
            }"
        >
            {{ user.receive_admin_emails ? "Unsubscribe" : "Subscribe" }}
        </button>
    </div>
</template>

<script type="text/babel">
import CheckIcon from "../UI/CheckIcon";
import MuteIcon from "../UI/MuteIcon";
import { notify } from "../Messaging/notify";
export default {
    props: ["user"],

    components: {
        CheckIcon,
        MuteIcon
    },

    data() {
        return {
            waiting: false
        };
    },

    methods: {
        toggle() {
            this.user.receive_admin_emails
                ? this.unsubscribe()
                : this.subscribe();
        },

        subscribe() {
            this.$store
                .dispatch("users/subscribeUserToEmails", {
                    user_id: this.user.id
                })
                .then(() => notify.success({ message: "Subscribed." }))
                .catch(() => notify.error({ message: "Failed to subscribe" }))
                .then(() => (this.waiting = false));
        },

        unsubscribe() {
            this.$store
                .dispatch("users/unsubscribeUserFromEmails", {
                    user_id: this.user.id
                })
                .then(() => notify.success({ message: "Unsubscribed." }))
                .catch(() => notify.error({ message: "Failed to unsubscribe" }))
                .then(() => (this.waiting = false));
        }
    }
};
</script>
