<template>
    <page v-if="ready">
        <page-header title="Edit customer"></page-header>
        <div>
            <form @submit.prevent="submit" class="max-w-md mx-auto">
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
                        @click="$router.push(`/customers/${$route.params.id}`)"
                    >
                        Back
                    </button>
                    <button
                        type="submit"
                        class="btn btn-navy"
                        :disabled="waiting"
                    >
                        Save & Exit
                    </button>
                </div>
            </form>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import { notify } from "../Messaging/notify";
import { clearFormErrors, showValidationErrors } from "../../libs/forms";
export default {
    components: {
        Page,
        PageHeader
    },

    data() {
        return {
            ready: false,
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

    watch: {
        $route() {
            this.ready = false;
            this.fetchCustomer();
        }
    },

    mounted() {
        this.fetchCustomer();
    },

    methods: {
        fetchCustomer() {
            this.$store
                .dispatch("customers/fetchCustomer", this.$route.params.id)
                .then(this.setForm)
                .catch(notify.error);
        },

        setForm({ name, email, phone }) {
            this.formData = { name, email, phone };
            this.ready = true;
        },

        submit() {
            this.waiting = true;
            this.formErrors = clearFormErrors(this.formErrors);

            this.$store
                .dispatch("customers/updateCustomer", {
                    id: this.$route.params.id,
                    formData: this.formData
                })
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess(message) {
            this.$router.push(`/customers/${this.$route.params.id}`);
            notify.success(message);
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = showValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            notify.error({ message: "Unable to save updated customer info." });
        }
    }
};
</script>
