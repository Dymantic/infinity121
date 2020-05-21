<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="New Unavailable Period"></page-header>
        <div>
            <p class="max-w-lg mb-6 text-gray-600">
                Enter the dates and times for which you won't be available.
            </p>
            <unavailable-period-form></unavailable-period-form>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import UnavailablePeriodForm from "./UnavailablePeriodForm";
export default {
    components: {
        PageHeader,
        UnavailablePeriodForm
    }
};
</script>
