<template>
    <span>
        <button @click="showForm = true" class="btn btn-red">Delete</button>
        <modal :show="showForm" @close="showForm = false">
            <div class="w-screen max-w-md p-6">
                <p class="text-lg font-bold text-red-500">Are you sure?</p>
                <p class="my-6">
                    You are about to delet {{ customer.name }}. This will remove
                    the customer and all their associated info from the system.
                    Are you sure you want to proceed?
                </p>
                <div class="my-6 flex justify-end">
                    <button @click="showForm = false" class="btn mr-4">
                        Cancel
                    </button>
                    <button @click="deleteCustomer" class="btn btn-red">
                        Yes, delete!
                    </button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["customer"],

    data() {
        return {
            showForm: false
        };
    },

    methods: {
        deleteCustomer() {
            this.$store
                .dispatch("customers/deleteCustomer", this.customer.id)
                .then(this.onSuccess)
                .catch(this.onError);
        },

        onSuccess(message) {
            this.showForm = false;
            notify.success(message);
            this.$router.push("/customers");
        },

        onError(message) {
            this.showForm = false;
            notify.error(message);
        }
    }
};
</script>
