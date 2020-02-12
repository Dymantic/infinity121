<template>
    <button :disabled="disabled" type="button" @click="$emit('toggle')" class="focus:outline-none">
        <span class="relative inline-block w-8">
            <span class="bar absolute left-0 w-8 h-2 bg-gray-400 rounded"/>
            <span class="knob absolute block w-4 h-4 rounded-full left-0" :class="knob_classes"/>
        </span>
    </button>
</template>

<script type="text/babel">
    export default {
        props: ['state', 'disabled'],

        computed: {
            knob_classes() {
                if(this.state === 'on') {
                    return 'knob-on bg-hms-navy';
                }

                if(this.state === 'off') {
                    return 'knob-off bg-gray-500';
                }

                return 'knob-unset bg-hard-ass-grey';
            }
        }
    }
</script>

<style scoped>
    .knob {
        top: -4px;
        left: -4px;
        transition: .3s ease-in-out;
    }

    .knob-on {
        transform: translateX(2rem);
    }

    .knob-off {
        transform: translateX(0);
    }

    .knob-unset {
        transform: translateX(1rem);
    }
</style>
