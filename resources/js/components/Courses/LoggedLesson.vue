<template>
    <page v-if="lesson">
        <page-header :title="`Lesson Log #${lesson.id}`"></page-header>
        <div class="">
            <div class="flex justify-between mb-12">
                <div class="flex items-center">
                    <p class="font-bold text-lg mr-4">Status:</p>
                    <course-status-badge
                        :status="lesson.status"
                    ></course-status-badge>
                </div>
                <div class="flex">
                    <p class="font-bold text-lg mr-4">Logged by:</p>
                    <div class="flex items-center">
                        <img
                            :src="lesson.teacher_avatar"
                            class="h-8 w-8 mr-4 rounded-full"
                        />
                        <p>{{ lesson.teacher_name }}</p>
                    </div>
                </div>
            </div>

            <p class="font-bold text-lg mb-2">Lesson date and times:</p>
            <p>
                The lesson was completed on
                <strong>{{ lesson.completed_on_pretty }}</strong
                >, between <strong>{{ lesson.actual_start }}</strong> and
                <strong>{{ lesson.actual_end }}</strong
                >.
            </p>
        </div>

        <div class="my-12">
            <p class="font-bold text-lg mb-2">Materials covered:</p>
            <p>{{ lesson.material_taught }}</p>
        </div>

        <div class="my-12">
            <p class="font-bold text-lg mb-2">
                Student participation and attitude
            </p>
            <p>{{ lesson.student_report }}</p>
        </div>

        <div class="my-12">
            <p class="font-bold text-lg mb-2">Teacher's notes:</p>
            <p>{{ lesson.teacher_log }}</p>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import CourseStatusBadge from "./CourseStatusBadge";
import { notify } from "../Messaging/notify";

export default {
    components: {
        Page,
        PageHeader,
        CourseStatusBadge
    },

    computed: {
        lesson() {
            return this.$store.getters["courses/loggedLessonById"](
                this.$route.params.id
            );
        }
    },

    mounted() {
        this.$store
            .dispatch("courses/fetchLoggedLessons")
            .catch(() =>
                notify.error({ message: "Unable to fetch logged lessons." })
            );
    }
};
</script>
