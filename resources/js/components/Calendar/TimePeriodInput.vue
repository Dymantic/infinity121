<template>
    <div class="flex items-center justify-between p-2">
        <div class="flex items-center">
            <span class="font-bold">From:</span>
            <select v-model="start_time" class="mx-4">
                <option
                    v-for="time in possible_times"
                    :key="time"
                    :value="time"
                    >{{ time }}</option
                >
            </select>

            <span class="font-bold">To:</span>
            <select v-model="end_time" class="mx-4">
                <option
                    v-for="time in possible_times"
                    :key="time"
                    :value="time"
                    >{{ time }}</option
                >
            </select>
        </div>

        <button :disabled="!canAdd" class="ml-4 btn btn-navy" @click="add">
            Add
        </button>
    </div>
</template>

<script type="text/babel">
import { TimePeriod } from "../../libs/Calendar/TimePeriod";
import { intToTimeString, everyHalfHour } from "../../libs/time_functions";

export default {
    data() {
        return {
            possible_times: everyHalfHour,
            start_time: null,
            end_time: null
        };
    },

    computed: {
        canAdd() {
            if (!this.start_time || !this.end_time) {
                return false;
            }

            return new TimePeriod(this.start_time, this.end_time).isValid();
        }
    },

    methods: {
        add() {
            this.$emit(
                "time-selected",
                new TimePeriod(this.start_time, this.end_time)
            );
            this.start_time = null;
            this.end_time = null;
        },

        timeAsString(time) {
            return intToTimeString(time);
        }
    }
};
</script>
