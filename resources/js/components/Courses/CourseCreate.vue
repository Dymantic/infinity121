<template>
    <page v-if="customer">
        <page-header title="New Course"></page-header>
        <course-manager
            :course="course"
            :customer="customer"
            @course-created="switchToEdit"
        ></course-manager>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import CourseManager from "./CourseManager";
import { notify } from "../Messaging/notify";
export default {
    components: {
        Page,
        PageHeader,
        CourseManager
    },

    data() {
        return {
            customer: null,
            course: null
        };
    },

    mounted() {
        this.$store
            .dispatch("customers/fetchCustomer", this.$route.params.customer)
            .then(customer => (this.customer = customer))
            .catch(notify.error);
    },

    methods: {
        switchToEdit(id) {
            this.$router.push(`/courses/${id}/edit`);
        }
    }
};
</script>
