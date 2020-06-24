<template>
    <page v-if="lesson">
        <page-header :title="`Lesson #${lesson.id}`"></page-header>
        <div>
            <p class="max-w-xl">
                This lesson should have been completed and logged by now, unless
                it was postponed by the teacher and/or student. If necessary,
                you may follow up with the teacher.
            </p>

            <div class="my-8">
                <p class="text-sm">Lesson Date:</p>
                <p class="text-xl font-bold">{{ lesson.lesson_date_pretty }}</p>
            </div>
            <div class="my-8">
                <p class="text-sm">Lesson Times:</p>
                <p class="text-xl font-bold">
                    {{ lesson.starts }} - {{ lesson.ends }}
                </p>
            </div>

            <div class="my-8">
                <p class="text-sm">Teacher</p>
                <div class="flex items-center pt-2">
                    <img
                        :src="lesson.teacher_avatar"
                        class="h-8 w-8 rounded-full mr-4"
                    />
                    <p>
                        <router-link
                            class="hover:text-blue-600 hover:underline"
                            :to="`/teachers/${lesson.teacher_id}`"
                            >{{ lesson.teacher_name }}</router-link
                        >
                    </p>
                </div>
            </div>
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
            return this.$store.getters["courses/dueLoggingById"](
                this.$route.params.id
            );
        }
    },

    mounted() {
        this.$store.dispatch("courses/fetchDueLoggingLessons").catch(() =>
            notify.error({
                message: "Unable to fetch lesson that are due to be logged."
            })
        );
    }
};
</script>
