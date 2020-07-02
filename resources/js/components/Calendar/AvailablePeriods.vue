<template>
    <page>
        <page-header title="Available Hours">
            <router-link to="/me/my-schedule" class="btn btn-navy"
                >See Schedule</router-link
            >
        </page-header>
        <p class="mb-8 max-w-2xl text-sm">
            These are the times you are generally available throughout the week.
            You will still be able to
            <router-link
                class="text-blue-600 hover:underline"
                to="/me/unavailable-periods"
                >mark yourself as unavailable</router-link
            >
            for specific dates or periods.
        </p>
        <div class="">
            <div v-for="day in days" :key="day.day" class="mb-6">
                <daily-hours :day="day"></daily-hours>
                <p class="text-center">
                    <router-link
                        :to="`/me/available-periods/edit/${day.day}`"
                        class="font-bold text-sm text-gray-600 underline hover:text-hms-navy"
                        >Edit
                    </router-link>
                </p>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import DailyHours from "./DailyHours";

export default {
    components: {
        Page,
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
