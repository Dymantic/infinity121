<template>
    <span>
        <button
            :disabled="waiting"
            class="btn btn-red"
            @click="showModal = true"
        >
            Delete
        </button>
        <modal :show="showModal" @close="showModal = false">
            <form @submit.prevent="submit" class="p-6 w-screen max-w-md">
                <p class="text-xl font-bold">Are you sure?</p>
                <p class="my-6">
                    You are about to delete this period. Are you sure this is
                    what you want to do?
                </p>
                <div class="flex justify-end mt-6">
                    <button
                        class="btn mr-4"
                        @click="showModal = false"
                        type="button"
                    >
                        Cancel
                    </button>
                    <button class="btn btn-red" type="submit">
                        Yes, do it!
                    </button>
                </div>
            </form>
        </modal>
    </span>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["period"],

    data() {
        return {
            showModal: false,
            waiting: false
        };
    },

    methods: {
        submit() {
            this.$store
                .dispatch("me/deleteUnavailablePeriod", this.period.id)
                .then(() => {
                    notify.success({ message: "Period has been deleted" });
                    this.$router.push("/me/unavailable-periods");
                })
                .catch(notify.error)
                .then(() => (this.waiting = false));
        }
    }
};
</script>
