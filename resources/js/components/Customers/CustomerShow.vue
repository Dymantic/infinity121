<template>
    <page v-if="customer">
        <page-header :title="customer.name">
            <delete-customer
                class="mr-4"
                :customer="customer"
            ></delete-customer>
            <router-link class="btn mr-4" :to="`/customers/${customer.id}/edit`"
                >Edit</router-link
            >
            <router-link
                class="btn btn-navy"
                :to="`/customers/${customer.id}/courses/create`"
                >New Course</router-link
            >
        </page-header>
        <div>
            <p><strong>Email: </strong>{{ customer.email }}</p>
            <p><strong>Phone: </strong>{{ customer.phone }}</p>
        </div>
        <div class="my-12">
            <table class="w-full text-sm shadow">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="text-left p-2">Students</th>
                        <th class="text-left p-2">Subject</th>
                        <th class="text-left p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(course, index) in courses"
                        :key="course.id"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td class="px-2 py-2">
                            <router-link :to="`/courses/${course.id}`">
                                {{ studentNames(course.students) }}
                            </router-link>
                        </td>
                        <td class="px-2 py-2">
                            {{ course.subject_title["en"] }}
                        </td>
                        <td class="px-2 py-2">
                            <course-status-badge
                                :status="course.status"
                            ></course-status-badge>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import DeleteCustomer from "./DeleteCustomer";
import CourseStatusBadge from "../Courses/CourseStatusBadge";
import { notify } from "../Messaging/notify";
import { fetchCustomerCourses } from "../../api/courses";
export default {
    components: {
        Page,
        PageHeader,
        DeleteCustomer,
        CourseStatusBadge
    },

    data() {
        return {
            courses: []
        };
    },

    mounted() {
        this.$store
            .dispatch("customers/fetchCustomers")
            .then(this.getCourses)
            .catch(notify.error);
    },

    computed: {
        customer() {
            return this.$store.getters["customers/customerById"](
                this.$route.params.id
            );
        }
    },

    methods: {
        getCourses() {
            fetchCustomerCourses(this.customer.id)
                .then(courses => (this.courses = courses))
                .catch(() =>
                    notify.error({
                        message: "Unable to fetch customer courses"
                    })
                );
        },

        studentNames(students) {
            const names = students.map(s => s.name);

            if (names.length > 2) {
                return `${names[0]}, ${names[1]} + ${names.length - 2} more`;
            }

            if (names.length === 2) {
                return `${names[0]} and ${names[1]}`;
            }

            return names[0];
        }
    }
};
</script>
