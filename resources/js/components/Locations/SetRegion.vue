<template>
    <span>
        <button
            @click="showForm = true"
            class="font-bold text-gray-600 hover:text-hms-navy"
        >
            <slot>Add Region</slot>
        </button>
        <modal :show="showForm" @close="showForm = false">
            <form @submit.prevent="submit" class="p-6 w-screen max-w-md">
                <p class="text-xl font-bold mb-6">{{ form_heading }}</p>
                <p>{{ form_text }}</p>
                <div
                    class="my-4"
                    :class="{ 'border-b border-red-400': formErrors.name }"
                >
                    <label class="form-label" for="name">Name of region</label>
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
                <div class="mt-6 flex justify-end">
                    <button
                        @click="showForm = false"
                        class="btn mr-4"
                        type="button"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-navy"
                        :disabled="waiting"
                    >
                        Add Region
                    </button>
                </div>
            </form>
        </modal>
    </span>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["country", "region"],

    data() {
        return {
            waiting: false,
            showForm: false,
            formData: {
                name: ""
            },
            formErrors: {
                name: ""
            }
        };
    },

    computed: {
        form_heading() {
            return !this.region
                ? `Add a new region`
                : `Update ${this.region.name}`;
        },

        form_text() {
            return !this.region
                ? `Add a new region in ${this.country.name} where Infinity is
                    available.`
                : ``;
        }
    },

    mounted() {
        if (this.region) {
            this.formData.name = this.region.name;
        }
    },

    methods: {
        submit() {
            const action = this.region ? "updateRegion" : "addRegion";
            const payload = this.region
                ? { region_id: this.region.id, formData: this.formData }
                : { country_id: this.country.id, formData: this.formData };
            this.formErrors = { name: "" };
            this.$store
                .dispatch(`locations/${action}`, payload)
                .then(this.onSuccess)
                .catch(this.onError);
        },

        onSuccess(message) {
            notify.success(message);
            this.showForm = false;
            if (!this.region) {
                this.formData = { name: "" };
            }
        },

        onError({ status, data }) {
            if (status === 422) {
                if (data.errors.hasOwnProperty("name")) {
                    this.formErrors.name = data.errors.name[0];
                }
                return;
            }
            notify.error({ message: "Unable to store new region." });
        }
    }
};
</script>
