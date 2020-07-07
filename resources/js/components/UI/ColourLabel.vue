<template>
    <span class="px-2 py-1 rounded-lg border" :class="colours">{{ text }}</span>
</template>

<script type="text/babel">
export default {
    props: ["text", "colour"],

    computed: {
        colours() {
            const lookup = {
                green: "bg-green-200 text-green-700 border-green-500",
                orange: "bg-orange-200 text-orange-700 border-orange-500",
                red: "bg-red-200 text-red-700 border-red-500",
                teal: "bg-teal-200 text-teal-700 border-teal-500",
                purple: "bg-purple-200 text-purple-700 border-purple-500",
                gray: "bg-gray-200 text-gray-700 border-gray-500"
            };

            return lookup[this.colour];
        }
    }
};
</script>
