<template>
    <div class="">
        <p class="text-center font-bold">{{ day.name() }}</p>
        <svg
            class="w-full h-auto border border-gray-100 my-3"
            viewBox="0 0 1450 100"
        >
            <g
                v-for="period in day.available_periods"
                :key="period.starts"
                v-if="period.isValid()"
            >
                <rect
                    :width="periodWidth(period)"
                    height="80"
                    :x="rectX(period)"
                    y="0"
                    class="fill-current text-green-200"
                ></rect>
            </g>

            <g v-if="showAll">
                <g
                    v-for="period in day.confirmed_periods"
                    :key="period.starts"
                    v-if="period.isValid()"
                >
                    <rect
                        :width="periodWidth(period)"
                        height="80"
                        :x="rectX(period)"
                        y="0"
                        class="fill-current text-purple-200"
                    ></rect>
                </g>

                <g
                    v-for="period in day.unconfirmed_periods"
                    :key="period.starts"
                    v-if="period.isValid()"
                >
                    <rect
                        :width="periodWidth(period)"
                        height="80"
                        :x="rectX(period)"
                        y="0"
                        class="fill-current text-orange-200"
                    ></rect>
                </g>
            </g>

            <g v-for="time in half_hours">
                <path
                    :d="`M ${time * 50} 0 L ${time * 50} 80`"
                    class="stroke-current text-gray-200"
                ></path>
                <text
                    v-if="time > 0 && time % 2 === 0"
                    :x="`${time * 50 - 8}`"
                    y="95"
                    class="font-bold text-gray-600 text-xl"
                >
                    {{ timeText(time) }}
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
    props: ["day", "show-all"],

    data() {
        return {
            half_hours: [
                0,
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                12,
                13,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
                25,
                26,
                27,
                28
            ]
        };
    },

    computed: {
        blocks() {
            return everyHalfHour;
        }
    },

    methods: {
        timeText(half_hour) {
            return `${half_hour / 2 + 8}`;
        },

        blockAsTimeString(time) {
            return intToTimeString(time);
        },

        periodWidth({ starts, ends }) {
            return ((timeToMinutes(ends) - timeToMinutes(starts)) / 30) * 50;
        },

        rectX({ starts }) {
            return ((timeToMinutes(starts) - timeToMinutes(800)) / 60) * 100;
        }
    }
};
</script>
