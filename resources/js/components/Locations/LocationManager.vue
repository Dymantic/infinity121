<template>
    <div class="max-w-4xl mx-auto px-6">
        <page-header title="Locations"></page-header>
        <div class="flex justify-between">
            <div class="w-64 h-screen bg-gray-200">
                <div v-for="country in countries" :key="country.id">
                    <router-link :to="`/locations/countries/${country.id}`">{{
                        country.name
                    }}</router-link>
                </div>
                <set-country></set-country>
            </div>
            <div class="flex-1 ml-6 bg-gray-200">
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
    }
};
</script>
