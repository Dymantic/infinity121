<template>
    <page>
        <page-header title="My Lessons"></page-header>
        <div>
            <p class="text-lg font-bold">Due Lessons</p>
            <div>
                <p v-if="ready && due_lessons.length === 0">
                    You have no upcoming lessons assigned to you.
                </p>
                <due-lesson
                    v-for="lesson in due_lessons"
                    :key="lesson.id"
                    :lesson="lesson"
                ></due-lesson>
            </div>
        </div>
        <div class="my-12">
            <p class="text-lg font-bold">Recently Completed Lessons</p>
            <p v-if="ready && completed_lessons.length === 0">
                You have not completed any lessons recently.
            </p>
            <table class="mt-6 w-full">
                <thead>
                    <tr class="bg-gray-100 shadow">
                        <th class="p-2 text-left uppercase font-normal text-sm">
                            Date
                        </th>
                        <th class="p-2 text-left uppercase font-normal text-sm">
                            Times
                        </th>
                        <th class="p-2 text-left uppercase font-normal text-sm">
                            Students
                        </th>
                        <th class="p-2 text-left uppercase font-normal text-sm">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr v-for="lesson in completed_lessons" :key="lesson.id">
                        <td class="p-2">
                            <router-link
                                class="text-blue-600 hover:underline font-bold"
                                :to="`/my-lesson-logs/${lesson.id}`"
                            >
                                {{ lesson.completed_on_pretty }}
                            </router-link>
                        </td>
                        <td class="p-2">
                            {{ lesson.actual_start }} - {{ lesson.actual_end }}
                        </td>
                        <td class="p-2">{{ studentNames(lesson.students) }}</td>
                        <td class="p-2">
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
import DueLesson from "./DueLesson";
import CourseStatusBadge from "../Courses/CourseStatusBadge";
import { notify } from "../Messaging/notify";
import { listStudentNames } from "../../libs/formatters";
export default {
    components: {
        Page,
        PageHeader,
        DueLesson,
        CourseStatusBadge
    },

    data() {
        return {
            ready: false
        };
    },

    computed: {
        due_lessons() {
            return this.$store.state.me.due_lessons;
        },

        completed_lessons() {
            return this.$store.state.me.completed_lessons;
        }
    },

    mounted() {
        Promise.all([this.fetchDue(), this.fetchCompleted()]).then(
            () => (this.ready = true)
        );
    },

    methods: {
        fetchDue() {
            return this.$store
                .dispatch("me/fetchDueLessons")
                .catch(() =>
                    notify.error({ message: "Unable to fetch due lessons" })
                );
        },

        fetchCompleted() {
            return this.$store.dispatch("me/fetchCompletedLessons").catch(() =>
                notify.error({
                    message: "Unable to fetch completed lessons"
                })
            );
        },

        studentNames(students) {
            return listStudentNames(students);
        }
    }
};
</script>
