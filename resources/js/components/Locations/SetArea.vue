<template>
    <span>
        <button
            @click="showForm = true"
            class="font-bold text-gray-600 hover:text-hms-navy"
        >
            <slot>
                + Add Area
            </slot>
        </button>
        <modal :show="showForm" @close="showForm = false">
            <form @submit.prevent="submit" class="p-6 w-screen max-w-md">
                <p class="text-xl font-bold mb-6">{{ form_heading }}</p>
                <p>{{ form_text }}</p>
                <div
                    class="my-4"
                    :class="{ 'border-b border-red-400': formErrors.name }"
                >
                    <label class="form-label" for="name">Name of area</label>
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
                        Add Area
                    </button>
                </div>
            </form>
        </modal>
    </span>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["region", "area"],

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
            return !this.area ? "Add an Area" : `Update ${this.area.name}`;
        },

        form_text() {
            return !this.area
                ? `Add a new area in ${
                      this.region.name
                  } where Infinity is available.`
                : ``;
        }
    },

    mounted() {
        if (this.area) {
            this.formData = { name: this.area.name };
        }
    },

    methods: {
        submit() {
            const action = this.area ? "updateArea" : "addArea";
            const payload = this.area
                ? { area_id: this.area.id, formData: this.formData }
                : { region_id: this.region.id, formData: this.formData };
            this.formErrors = { name: "" };
            this.$store
                .dispatch(`locations/${action}`, payload)
                .then(this.onSuccess)
                .catch(this.onError);
        },

        onSuccess(message) {
            notify.success(message);
            this.showForm = false;
            if (!this.area) {
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
            notify.error({ message: "Unable to store region." });
        }
    }
};
</script>
