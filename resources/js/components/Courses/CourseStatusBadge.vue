<template>
    <span class="text-xs px-2 py-1 rounded-lg border" :class="colourClasses">
        {{ status }}
    </span>
</template>

<script type="text/babel">
export default {
    props: ["status"],

    computed: {
        colourClasses() {
            const lookup = {
                unconfirmed: "bg-orange-200 border-orange-500 text-orange-700",
                confirmed: "bg-indigo-200 border-indigo-500 text-indigo-700",
                ongoing: "bg-green-200 border-green-500 text-green-700"
            };

            return lookup[this.status];
        }
    }
};
</script>
