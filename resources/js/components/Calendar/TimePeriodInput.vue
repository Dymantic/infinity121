<template>
    <div class="flex items-center p-2">
        <span class="font-bold">From:</span>
        <select ref="start" @input="handleChange" class="mx-4">
            <option
                :selected="time === value.starts"
                v-for="time in possible_times"
                :key="time"
                :value="time"
                >{{ timeAsString(time) }}</option
            >
        </select>

        <span class="font-bold">To:</span>
        <select ref="end" @input="handleChange" class="mx-4">
            <option
                v-for="time in possible_times"
                :selected="time === value.ends"
                :key="time"
                :value="time"
                >{{ timeAsString(time) }}</option
            >
        </select>
    </div>
</template>

<script type="text/babel">
import { TimePeriod } from "../../libs/Calendar/TimePeriod";
import { intToTimeString, everyHalfHour } from "../../libs/time_functions";

export default {
    props: ["value"],

    data() {
        return {
            possible_times: everyHalfHour
        };
    },

    methods: {
        handleChange() {
            this.$emit(
                "input",
                new TimePeriod(this.$refs.start.value, this.$refs.end.value)
            );
        },

        timeAsString(time) {
            return intToTimeString(time);
        }
    }
};
</script>
