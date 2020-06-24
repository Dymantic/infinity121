<template>
    <div class="max-w-5xl mx-auto px-6" v-if="ready">
        <page-header :title="`Set Hours for ${dayName}`">
            <router-link class="btn" to="/me/available-periods"
                >Cancel</router-link
            >
            <button class="btn btn-navy ml-4" @click="saveAndExit">
                Save and Exit
            </button>
        </page-header>

        <div class="flex justify-between">
            <div class="">
                <div class="mb-10">
                    <p class="max-w-md">
                        Enter a time period below. You may remove added time
                        periods as you need.
                    </p>
                    <time-period-input
                        class="shadow my-6"
                        @time-selected="addPeriod"
                    ></time-period-input>
                </div>
            </div>

            <div>
                <p class="mb-6 font-bold text-gray-600">
                    Available periods for this day:
                </p>
                <p class="mb-8 max-w-2xl" v-if="periods.length === 0">
                    You currently have no periods allocated on
                    {{ dayName }}.
                </p>
                <div
                    v-for="(period, index) in ordered_periods"
                    :key="index"
                    class="mb-4 w-64 py-2 pl-2 flex justify-between items-center"
                    :class="{
                        'bg-shady-blue': period.isValid(),
                        'bg-red-100': !period.isValid()
                    }"
                >
                    <p class="font-bold">{{ period.asString() }}</p>
                    <button
                        class="w-6 h-6 rounded-full text-red-500 bg-white flex items-center justify-center cursor-pointer hover:bg-red-100 mr-2"
                        @click="removePeriod(index)"
                    >
                        &times;
                    </button>
                </div>
            </div>
        </div>

        <div>
            <daily-hours :day="currentDay" class="border"></daily-hours>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import {
    intToTimeString,
    dayNameFromInt,
    everyHalfHour
} from "../../libs/time_functions";
import TimePeriodInput from "./TimePeriodInput";
import { TimePeriod } from "../../libs/Calendar/TimePeriod";
import { notify } from "../Messaging/notify";
import { Day } from "../../libs/Calendar/Day";
import DailyHours from "./DailyHours";
import { intByPropertyName } from "../../libs/sorting";

export default {
    components: {
        PageHeader,
        TimePeriodInput,
        DailyHours
    },

    data() {
        return {
            periods: [],
            ready: false
        };
    },

    computed: {
        dayName() {
            return dayNameFromInt(this.$route.params.day);
        },

        currentDay() {
            return new Day(this.$route.params.day, this.periods.map(p => p));
        },

        ordered_periods() {
            return this.periods.sort(intByPropertyName("starts"));
        }
    },

    mounted() {
        this.resetForm();
    },

    watch: {
        "$route.params.day": function() {
            this.resetForm();
        }
    },

    methods: {
        addPeriod(period) {
            this.periods.push(period);
        },

        timeAsString(time) {
            return intToTimeString(time);
        },

        resetForm() {
            this.ready = false;
            this.$store
                .dispatch("me/fetchAvailablePeriods")
                .then(() => {
                    this.periods = this.$store.getters[
                        "me/availablePeriodsForDay"
                    ](this.$route.params.day);
                    this.ready = true;
                })
                .catch(
                    err =>
                        console.log(err) ||
                        notify.error({
                            message: "Unable to get current hours for the day"
                        })
                );
        },

        removePeriod(index) {
            this.periods.splice(index, 1);
        },

        saveAndExit() {
            this.$store
                .dispatch("me/setDailyAvailablePeriods", {
                    day_of_week: parseInt(this.$route.params.day),
                    periods: this.periods.map(p => p.asTimePair())
                })
                .then(() =>
                    notify.success({ message: "Hours have been updated." })
                )
                .catch(notify.error);

            this.$router.push("/me/available-periods");
        }
    }
};
</script>
