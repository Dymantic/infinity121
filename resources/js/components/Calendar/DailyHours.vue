<template>
    <div class="">
        <p class="text-center font-bold">{{ day.name() }}</p>
        <svg height="400" viewBox="0 0 250 840">
            <g
                v-for="period in day.periods"
                :key="period.starts"
                v-if="period.isValid()"
            >
                <rect
                    width="250"
                    :height="periodHeight(period)"
                    x="0"
                    :y="rectY(period)"
                    class="fill-current text-green-200"
                ></rect>
                <text
                    x="25"
                    :y="textY(period)"
                    class="text-2xl font-bold text-green-800"
                >
                    {{ period.asString() }}
                </text>
            </g>
        </svg>
    </div>
</template>

<script type="text/babel">
import {
    intToTimeString,
    everyHalfHour,
    timeToMinutes
} from "../../libs/time_functions";

export default {
    props: ["day"],

    computed: {
        blocks() {
            return everyHalfHour;
        }
    },

    methods: {
        blockAsTimeString(time) {
            return intToTimeString(time);
        },

        periodHeight({ starts, ends }) {
            return timeToMinutes(ends) - timeToMinutes(starts);
        },

        rectY({ starts }) {
            return timeToMinutes(starts) - timeToMinutes(800);
        },

        textY(period) {
            return this.rectY(period) + this.periodHeight(period) / 2 + 10;
        }
    }
};
</script>
