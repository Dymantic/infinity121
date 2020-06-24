<template>
    <page>
        <page-header title="Active Courses"></page-header>

        <div class="">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Customer
                        </th>
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Students
                        </th>
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Teacher
                        </th>
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Start Date
                        </th>
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Status
                        </th>
                        <th class="p-2 text-left font-normal uppercase text-xs">
                            Progress
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr
                        v-for="(course, index) in courses"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td class="px-2 p-1">
                            <router-link
                                class="hover:text-blue-600 hover:underline"
                                :to="`/customers/${course.customer_id}`"
                                >{{ course.customer.name }}</router-link
                            >
                        </td>
                        <td class="px-2 p-1">
                            <router-link
                                class="hover:text-blue-600 hover:underline"
                                :to="`/courses/${course.id}`"
                            >
                                {{ studentNames(course.students) }}
                            </router-link>
                        </td>
                        <td class="px-2 p-1">
                            <router-link
                                class="hover:text-blue-600 hover:underline"
                                :to="`/teachers/${course.profile_id}`"
                            >
                                {{ course.teacher_name }}
                            </router-link>
                        </td>
                        <td class="px-2 p-1">
                            {{ course.starts_from }}
                        </td>
                        <td class="px-2 p-1">
                            <course-status-badge
                                :status="course.status"
                            ></course-status-badge>
                        </td>
                        <td class="px-2 p-1">
                            {{ course.lessons.filter(l => l.complete).length }}
                            of {{ course.total_lessons }}
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
import CourseStatusBadge from "./CourseStatusBadge";
import { notify } from "../Messaging/notify";
import { listStudentNames } from "../../libs/formatters";

export default {
    components: {
        Page,
        PageHeader,
        CourseStatusBadge
    },

    computed: {
        courses() {
            return this.$store.state.courses.active;
        }
    },

    mounted() {
        this.$store
            .dispatch("courses/fetchActive")
            .catch(() =>
                notify.error({ message: "Unable to fetch active courses." })
            );
    },

    methods: {
        studentNames(students) {
            return listStudentNames(students);
        }
    }
};
</script>
