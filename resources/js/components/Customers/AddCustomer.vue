<template>
    <span>
        <button @click="showForm = true" class="btn btn-navy">
            Add Customer
        </button>
        <modal :show="showForm" @close="showForm = false">
            <form @submit.prevent="submit" class="max-w-md w-screen p-6">
                <p class="text-lg font-bold text-hms-navy">
                    Add a new Customer
                </p>
                <div
                    class="my-4"
                    :class="{ 'border-b border-red-400': formErrors.name }"
                >
                    <label class="form-label" for="name">Name</label>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.name"
                        >{{ formErrors.name }}</span
                    >
                    <input
                        type="text"
                        name="name"
                        v-model="formData.name"
                        class="input-text"
                        id="name"
                    />
                </div>
                <div
                    class="my-4"
                    :class="{ 'border-b border-red-400': formErrors.email }"
                >
                    <label class="form-label" for="email">Email</label>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.email"
                        >{{ formErrors.email }}</span
                    >
                    <input
                        type="email"
                        name="email"
                        v-model="formData.email"
                        class="input-text"
                        id="email"
                    />
                </div>
                <div
                    class="my-4"
                    :class="{ 'border-b border-red-400': formErrors.phone }"
                >
                    <label class="form-label" for="phone">Phone</label>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.phone"
                        >{{ formErrors.phone }}</span
                    >
                    <input
                        type="text"
                        name="phone"
                        v-model="formData.phone"
                        class="input-text"
                        id="phone"
                    />
                </div>
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        class="btn mr-4"
                        @click="showForm = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-navy"
                        :disabled="waiting"
                    >
                        Create Customer
                    </button>
                </div>
            </form>
        </modal>
    </span>
</template>

<script type="text/babel">
import { showValidationErrors, clearFormErrors } from "../../libs/forms";
import { notify } from "../Messaging/notify";

export default {
    data() {
        return {
            showForm: false,
            waiting: false,
            formData: {
                name: "",
                email: "",
                phone: ""
            },
            formErrors: {
                name: "",
                email: "",
                phone: ""
            }
        };
    },

    methods: {
        submit() {
            this.formErrors = clearFormErrors(this.formErrors);
            this.waiting = true;

            this.$store
                .dispatch("customers/addCustomer", this.formData)
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => {
                    this.waiting = false;
                });
        },

        onSuccess(message) {
            notify.success(message);
            this.formData = {
                name: "",
                email: "",
                phone: ""
            };
            this.showForm = false;
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = showValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            notify.error({ message: "Unable to save customer." });
            this.showForm = false;
        }
    }
};
</script>
