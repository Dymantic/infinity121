<template>
    <div>
        <p class="text-lg font-bold mb-6">Course Location</p>
        <p v-if="formErrors.area_id" class="my-1 text-xs text-red-500">
            {{ formErrors.area_id }}
        </p>
        <div class="my-6 flex">
            <div>
                <label for="country" class="form-label font-bold text-xs"
                    >Country</label
                >
                <select
                    v-model="formData.country_id"
                    id="country"
                    class="input-text w-auto px-4"
                    @change="clearRegion"
                >
                    <option
                        :value="country.id"
                        v-for="country in countries"
                        :key="country.id"
                        >{{ country.name }}</option
                    >
                </select>
            </div>
            <div class="mx-4">
                <label for="region" class="form-label font-bold text-xs"
                    >Region</label
                >
                <select
                    v-model="formData.region_id"
                    id="region"
                    class="input-text w-auto px-4"
                    @change="clearArea"
                >
                    <option
                        :value="region.id"
                        v-for="region in regions"
                        :key="region.id"
                        >{{ region.name }}</option
                    >
                </select>
            </div>
            <div>
                <label for="area" class="form-label font-bold text-xs"
                    >Area</label
                >
                <select
                    v-model="formData.area_id"
                    id="area"
                    class="input-text w-auto px-4"
                >
                    <option
                        :value="area.id"
                        v-for="area in areas"
                        :key="area.id"
                        >{{ area.area_name }}</option
                    >
                </select>
            </div>
        </div>
        <div
            class="my-4"
            :class="{ 'border-b border-red-400': formErrors.address }"
        >
            <label class="form-label" for="address">Address</label>
            <span class="text-xs text-red-500" v-show="formErrors.address">{{
                formErrors.address
            }}</span>
            <input
                type="text"
                name="address"
                v-model="formData.address"
                class="input-text"
                id="address"
            />
        </div>
        <div
            class="my-4"
            :class="{ 'border-b border-red-400': formErrors.map_link }"
        >
            <label class="form-label" for="map_link">Map link</label>
            <span class="text-xs text-red-500" v-show="formErrors.map_link">{{
                formErrors.map_link
            }}</span>
            <input
                type="text"
                name="map_link"
                v-model="formData.map_link"
                class="input-text"
                id="map_link"
            />
        </div>
        <div
            class="my-4"
            :class="{ 'border-b border-red-400': formErrors.location_notes }"
        >
            <label class="form-label" for="location_notes"
                >Location notes</label
            >
            <span
                class="text-xs text-red-500"
                v-show="formErrors.location_notes"
                >{{ formErrors.location_notes }}</span
            >
            <textarea
                name="location_notes"
                v-model="formData.location_notes"
                class="input-text h-32 resize-none"
                id="location_notes"
            />
        </div>
        <div class="my-6 flex justify-end">
            <button @click="save" class="btn btn-navy">Save</button>
        </div>
    </div>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["course"],

    data() {
        return {
            formData: {
                country_id: null,
                region_id: null,
                area_id: null,
                address: "",
                map_link: "",
                location_notes: ""
            },
            formErrors: {
                area_id: "",
                address: "",
                map_link: "",
                location_notes: ""
            }
        };
    },

    computed: {
        countries() {
            return this.$store.state.locations.countries;
        },

        regions() {
            if (!this.formData.country_id) {
                return [];
            }
            return this.$store.getters["locations/regionsOfCountry"](
                this.formData.country_id
            );
        },

        areas() {
            if (!this.formData.region_id) {
                return [];
            }
            return this.$store.getters["locations/areasOfRegion"](
                this.formData.region_id
            );
        }
    },

    watch: {
        course() {
            this.setForm();
        }
    },

    mounted() {
        if (this.course) {
            this.setForm();
        }
    },

    methods: {
        setForm() {
            this.formData = {
                country_id: this.course.area_id
                    ? this.course.area.country_id
                    : null,
                region_id: this.course.area_id
                    ? this.course.area.region_id
                    : null,
                area_id: this.course.area_id,
                address: this.course.address,
                map_link: this.course.map_link,
                location_notes: this.course.location_notes
            };
        },

        clearRegion() {
            this.formData.region_id = null;
            this.formData.area_id = null;
        },

        clearArea() {
            this.formData.area_id = null;
        },

        save() {
            this.clearValidationErrors();

            const data = {
                area_id: this.formData.area_id,
                address: this.formData.address,
                map_link: this.formData.map_link,
                location_notes: this.formData.location_notes
            };

            this.$store
                .dispatch("customers/updateCourseLocation", {
                    course_id: this.course.id,
                    formData: data
                })
                .then(() => {
                    notify.success({ message: "course location updated" });
                    this.$emit("updated");
                })
                .catch(this.onError);
        },

        onError({ status, data }) {
            if (status === 422) {
                return this.showValidationErrors(data.errors);
            }
            notify.error({ message: "Unable to update course location." });
        },

        showValidationErrors(errors) {
            Object.keys(errors).forEach(key => {
                if (key === "area_id") {
                    this.formErrors.area_id =
                        "Please ensure you have a valid location selected";
                }
                if (key === "address") {
                    this.formErrors.address = "The address is required.";
                }
                if (key === "map_link") {
                    this.formErrors.map_link =
                        "Please ensure you have a valid map link";
                }
                if (key === "location_notes") {
                    this.formErrors.location_notes =
                        "There is a problem with your location notes.";
                }
            });
        },

        clearValidationErrors() {
            this.formErrors = {
                area_id: "",
                address: "",
                map_link: "",
                location_notes: ""
            };
        }
    }
};
</script>
