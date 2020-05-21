<template>
    <div class="max-w-4xl mx-auto" v-if="period">
        <page-header title="Edit Unavailable Period">
            <delete-button :period="period"></delete-button>
        </page-header>
        <div>
            <p class="max-w-lg mb-6 text-gray-600">
                Enter the dates and times for which you won't be available.
            </p>
            <unavailable-period-form :period="period"></unavailable-period-form>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import UnavailablePeriodForm from "./UnavailablePeriodForm";
import DeleteUnavailablePeriod from "./DeleteUnavailablePeriod";
export default {
    components: {
        PageHeader,
        UnavailablePeriodForm,
        "delete-button": DeleteUnavailablePeriod
    },

    computed: {
        period() {
            return this.$store.getters["me/unavailablePeriodById"](
                this.$route.params.id
            );
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchUnavailablePeriods");
    }
};
</script>
