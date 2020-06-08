<template>
    <span>
        <button class="btn btn-red" @click="showModal = true">Delete</button>
        <modal :show="showModal" @close="showModal = false">
            <div class="p-6 w-screen max-w-md">
                <p class="text-lg font-bold text-red-600">Are you sure?</p>
                <p class="my-6">
                    You are about to remove {{ user.name }} from the system.
                    They will no longer be able to login or perform any actions
                    on the site.
                </p>
                <div class="flex justify-end">
                    <button @click="showModal = false" class="btn mr-4">
                        Cancel
                    </button>
                    <button @click="removeUser" class="btn btn-red">
                        Yes, do it!
                    </button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["user"],

    data() {
        return {
            showModal: false
        };
    },

    methods: {
        removeUser() {
            this.$store
                .dispatch("users/removeUser", this.user.id)
                .then(() => {
                    this.$router.push("/users");
                    notify.success({ message: "User has been deleted." });
                })
                .catch(notify.error);
        }
    }
};
</script>
