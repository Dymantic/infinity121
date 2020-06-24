<template>
    <page v-if="lesson">
        <page-header :title="`Lesson Log #${lesson.id}`">
            <router-link class="btn btn-navy" to="/me/my-lessons"
                >Back to lessons</router-link
            >
        </page-header>
        <div class="my-12">
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
import { notify } from "../Messaging/notify";

export default {
    components: {
        Page,
        PageHeader
    },

    computed: {
        lesson() {
            return this.$store.getters["me/loggedLessonById"](
                this.$route.params.id
            );
        }
    },

    mounted() {
        this.$store
            .dispatch("me/fetchCompletedLessons")
            .catch(() =>
                notify.error({ message: "Unable to fetch completed lessons." })
            );
    }
};
</script>
