<template>
    <page v-if="course">
        <page-header title="Edit course">
            <router-link :to="`/courses/${course.id}`" class="btn btn-navy"
                >See Course</router-link
            >
        </page-header>
        <course-manager
            :customer="course.customer"
            :course="course"
            @updated="fetchCourse"
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
            course: null
        };
    },

    mounted() {
        this.fetchCourse();
    },

    methods: {
        fetchCourse() {
            this.$store
                .dispatch("customers/fetchCourse", this.$route.params.course)
                .then(course => (this.course = course))
                .catch(notify.error);
        }
    }
};
</script>
