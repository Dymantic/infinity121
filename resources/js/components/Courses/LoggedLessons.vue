<template>
    <page>
        <page-header title="Recently Logged Lessons"></page-header>
        <div>
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="p-2 text-left uppercase text-xs bg-blue-100">
                            Students
                        </td>
                        <td class="p-2 text-left uppercase text-xs bg-blue-100">
                            Teacher
                        </td>
                        <td class="p-2 text-left uppercase text-xs bg-blue-100">
                            Lesson Completed
                        </td>
                        <td class="p-2 text-left uppercase text-xs bg-blue-100">
                            Status
                        </td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr
                        v-for="(lesson, index) in lessons"
                        :key="lesson.id"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td class="px-2 py-1">
                            <router-link
                                :to="`/logged-lessons/${lesson.id}`"
                                class="hover:text-blue-600 hover:underline"
                            >
                                {{ studentNames(lesson.students) }}
                            </router-link>
                        </td>
                        <td class="px-2 py-1">
                            <router-link
                                :to="`/teachers/${lesson.profile_id}`"
                                class="hover:text-blue-600 hover:underline"
                            >
                                {{ lesson.teacher_name }}
                            </router-link>
                        </td>
                        <td class="px-2 py-1">
                            {{ lesson.completed_on_pretty }}
                        </td>
                        <td class="px-2 py-1">
                            <course-status-badge
                                :status="lesson.status"
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
        lessons() {
            return this.$store.state.courses.logged_lessons;
        }
    },

    mounted() {
        this.$store
            .dispatch("courses/fetchLoggedLessons")
            .catch(() =>
                notify.error({ message: "Unable to fetch logged lessons." })
            );
    },

    methods: {
        studentNames(students) {
            return listStudentNames(students);
        }
    }
};
</script>
