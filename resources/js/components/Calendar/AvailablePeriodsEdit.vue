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
            <div>
                <p class="mb-8 max-w-2xl" v-if="periods.length === 0">
                    You currently have no periods allocated on {{ dayName }}.
                    Click add period below to get started.
                </p>
                <div
                    v-for="(period, index) in ordered_periods"
                    :key="index"
                    class="mb-4 w-80 flex justify-between items-center"
                    :class="{
                        'bg-shady-blue': period.times.isValid(),
                        'bg-red-100': !period.times.isValid()
                    }"
                >
                    <time-period-input
                        v-model="period.times"
                    ></time-period-input>
                    <button
                        class="w-6 h-6 rounded-full text-red-500 bg-white flex items-center justify-center cursor-pointer hover:bg-red-100 mr-2"
                        @click="removePeriod(index)"
                    >
                        &times;
                    </button>
                </div>
                <button
                    @click="addPeriod"
                    class="font-bold underline hover:text-hms-navy"
                >
                    Add period
                </button>
            </div>
            <div>
                <daily-hours :day="currentDay" class="border"></daily-hours>
            </div>
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
            possible_times: everyHalfHour,
            periods: [],
            ready: false
        };
    },

    computed: {
        dayName() {
            return dayNameFromInt(this.$route.params.day);
        },

        currentDay() {
            return new Day(
                this.$route.params.day,
                this.periods.map(p => p.times)
            );
        },

        ordered_periods() {
            return this.periods.sort(intByPropertyName("times.starts"));
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
        timeAsString(time) {
            return intToTimeString(time);
        },

        resetForm() {
            this.ready = false;
            this.$store
                .dispatch("me/fetchAvailablePeriods")
                .then(() => {
                    this.setInitialPeriods(
                        this.$store.getters["me/availablePeriodsForDay"](
                            this.$route.params.day
                        )
                    );
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

        setInitialPeriods(periods) {
            this.periods = periods.map(p => ({ times: p }));
        },

        addPeriod() {
            this.periods.push({ times: new TimePeriod(800, 800) });
        },

        removePeriod(index) {
            this.periods.splice(index, 1);
        },

        saveAndExit() {
            this.$store
                .dispatch("me/setDailyAvailablePeriods", {
                    day_of_week: parseInt(this.$route.params.day),
                    periods: this.periods.map(p => p.times.asTimePair())
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
