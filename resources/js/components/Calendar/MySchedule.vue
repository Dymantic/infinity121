<template>
    <page>
        <page-header title="My Schedule"></page-header>

        <p class="mb-8 max-w-2xl text-sm">
            This is your current schedule. You are automatically unavailable
            during times when you have lesson assigned to you, and once those
            lessons are done, the time will be added back to your schedule. You
            may also
            <router-link
                class="text-blue-600 hover:underline"
                to="/me/available-periods"
                >edit your available times</router-link
            >.
        </p>

        <div class="flex mb-6">
            <div class="flex items-center mr-6">
                <div class="w-4 h-4 rounded-full bg-green-200 mr-3"></div>
                <p>Available Times</p>
            </div>
            <div class="flex items-center mr-6">
                <div class="w-4 h-4 rounded-full bg-purple-200 mr-3"></div>
                <p>Confirmed lessons</p>
            </div>
            <div class="flex items-center mr-6">
                <div class="w-4 h-4 rounded-full bg-orange-200 mr-3"></div>
                <p>Unconfirmed lessons</p>
            </div>
        </div>

        <div class="">
            <div v-for="day in days" :key="day.day" class="mb-6">
                <daily-hours :day="day" :show-all="true"></daily-hours>
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
            return this.$store.getters["me/currentScheduleSummary"];
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchSchedule");
    },

    methods: {}
};
</script>
