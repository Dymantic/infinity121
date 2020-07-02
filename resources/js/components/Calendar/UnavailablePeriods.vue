<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="Unavailable Periods">
            <router-link
                to="/me/unavailable-periods/create"
                class="btn btn-navy"
                >Add New</router-link
            >
        </page-header>
        <p class="mb-6 max-w-xl text-gray-600">
            These are the periods when you will not be available to teach. This
            is just to inform Infinity so that they do not assign you to classes
            you cannot take. Note that this does <strong>NOT</strong> excuse you
            from already arranged lessons.
        </p>
        <div class="my-12">
            <div
                v-for="period in periods"
                :key="period.id"
                class="p-4 shadow max-w-md mb-8"
            >
                <div class="flex justify-between">
                    <div class="mr-6">
                        <p class="text-sm font-bold text-gray-600">From:</p>
                        <p>{{ period.starts_pretty }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-600">To:</p>
                        <p>{{ period.ends_pretty }}</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <router-link
                        :to="`/me/unavailable-periods/${period.id}/edit`"
                        class="font-bold text-gray-600 hover:text-hms-navy"
                        >Edit</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import { notify } from "../Messaging/notify";
export default {
    components: {
        PageHeader
    },

    computed: {
        periods() {
            return this.$store.state.me.unavailable_periods;
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchUnavailablePeriods").catch(notify.error);
    }
};
</script>
