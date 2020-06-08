<template>
    <div class="max-w-4xl mx-auto px-6" v-if="countries && ready">
        <page-header title="Choose where you work">
            <button class="btn btn-navy" @click="save">Save</button>
        </page-header>
        <p class="text-lg mb-12 max-w-2xl">
            Please select the areas in which you are able to teach. Please only
            select areas where you are permanently based.
        </p>
        <div class="flex justify-between">
            <div class="w-64 mr-6 h-64">
                <div v-for="country in countries" :key="country.id">
                    <button
                        @click="selected_country = country.id"
                        class="focus:outline-none mb-2"
                    >
                        <span class="font-bold">{{ country.name }}</span>
                        <span
                            class="inline-block h-2 w-2 rounded-full bg-hms-navy ml-2"
                            v-show="selected_country === country.id"
                        ></span>
                    </button>
                </div>
            </div>
            <div class="flex-1 ml-6">
                <div v-show="!selected_country">
                    <p>Select a country on the left to get started.</p>
                </div>
                <div
                    v-for="country in countries"
                    :key="country.id"
                    v-show="country.id === selected_country"
                >
                    <p class="text-xl font-bold border-b border-gray-200">
                        {{ country.name }}
                    </p>
                    <div
                        v-for="region in country.regions"
                        :key="region.id"
                        class="pl-4 my-6"
                    >
                        <p class="text-lg font-bold border-b border-gray-200">
                            {{ region.name }}
                        </p>
                        <div
                            v-for="area in region.areas"
                            :key="area.id"
                            class="py-1 flex items-center"
                        >
                            <input
                                type="checkbox"
                                :value="area.id"
                                v-model="formData.working_areas"
                                :id="`area_${area.id}`"
                                class="mr-2"
                            />
                            <label :for="`area_${area.id}`">{{
                                area.area_name
                            }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import { notify } from "../Messaging/notify";
export default {
    components: {
        PageHeader
    },

    data() {
        return {
            selected_country: null,
            ready: false,
            formData: {
                working_areas: []
            }
        };
    },

    computed: {
        countries() {
            return this.$store.state.locations.countries;
        }
    },

    mounted() {
        this.$store.dispatch("locations/fetchLocations").catch(notify.error);
        this.$store.dispatch("me/fetchProfile").then(this.setForm);
    },

    methods: {
        setForm() {
            const areas = this.$store.getters["me/myWorkingAreas"];

            this.formData.working_areas = areas.map(a => a.id);
            if (areas.length > 0) {
                this.selected_country = areas[0].country_id;
            }

            this.ready = true;
        },

        save() {
            this.$store
                .dispatch("me/setWorkingAreas", this.formData.working_areas)
                .then(notify.success)
                .catch(notify.error);
        }
    }
};
</script>
