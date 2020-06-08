<template>
    <span>
        <button @click="showModal = true">
            <delete-icon
                class="text-gray-600 hover:text-red-500"
                :class="{ 'h-4': size !== 'small', 'h-3': size === 'small' }"
            ></delete-icon>
        </button>
        <modal :show="showModal" @close="showModal = false">
            <form @submit.prevent="submit" class="w-screen max-w-md p-6">
                <p>
                    You are about to delete {{ name }}. This will also delete
                    all its associated locations. Are you sure you want to
                    proceed?
                </p>
                <div class="flex justify-end mt-6">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="btn mx-4"
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
import DeleteIcon from "../UI/DeleteIcon";
import { notify } from "../Messaging/notify";
export default {
    components: {
        DeleteIcon
    },

    props: ["type", "name", "item-id", "size"],

    data() {
        return {
            showModal: false
        };
    },

    computed: {
        delete_action() {
            const actions = {
                country: "locations/deleteCountry",
                region: "locations/deleteRegion",
                area: "locations/deleteArea"
            };

            return actions[this.type];
        }
    },

    methods: {
        submit() {
            this.$store
                .dispatch(this.delete_action, this.itemId)
                .then(this.onDeleted)
                .catch(notify.error)
                .then(() => (this.showModal = false));
        },

        onDeleted() {
            notify.success({ message: `${this.name} has been deleted.` });
            if (this.type === "country") {
                this.$router.push("/locations");
            }
        }
    }
};
</script>
