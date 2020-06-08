<template>
    <div class="max-w-4xl mx-auto px-6">
        <page-header title="Locations"></page-header>
        <div class="flex justify-between">
            <div class="w-64 h-screen">
                <div
                    v-for="country in countries"
                    :key="country.id"
                    class="flex items-center mb-2"
                >
                    <router-link
                        :to="`/locations/countries/${country.id}`"
                        class="text-lg block font-bold text-gray-700 hover:text-hms-navy"
                        >{{ country.name }}</router-link
                    >
                    <div
                        v-show="showingCountry(country.id)"
                        class="h-2 w-2 ml-3 rounded-full bg-hms-navy"
                    ></div>
                </div>
                <div class="my-6">
                    <set-country></set-country>
                </div>
            </div>
            <div class="flex-1 ml-6">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import SetCountry from "./SetCountry";
import { notify } from "../Messaging/notify";
export default {
    components: {
        PageHeader,
        SetCountry
    },

    computed: {
        countries() {
            return this.$store.state.locations.countries;
        }
    },

    mounted() {
        this.$store.dispatch("locations/fetchLocations").catch(notify.error);
    },

    methods: {
        showingCountry(id) {
            return (
                this.$route.params.id && parseInt(this.$route.params.id) === id
            );
        }
    }
};
</script>
