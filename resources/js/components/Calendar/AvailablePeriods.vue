<template>
    <div class="max-w-4xl mx-auto pb-20">
        <page-header title="Available Hours"></page-header>
        <p class="mb-8 max-w-2xl text-lg">
            These are the times you are general availability throughout the
            week. You will still be able to mark yourself as unavailable for
            specific dates or periods.
        </p>
        <div class="flex">
            <div v-for="day in days" :key="day.day" class="border">
                <daily-hours :day="day"></daily-hours>
                <p class="text-center">
                    <router-link
                        :to="`/me/available-periods/edit/${day.day}`"
                        class="font-bold text-sm text-gray-600 underline hover:text-hms-navy"
                        >Edit</router-link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import DailyHours from "./DailyHours";

export default {
    components: {
        PageHeader,
        DailyHours
    },

    data() {
        return {};
    },

    computed: {
        days() {
            return this.$store.getters["me/availableHoursSummary"];
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchAvailablePeriods");
    },

    methods: {}
};
</script>
