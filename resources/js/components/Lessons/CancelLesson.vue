<template>
    <page v-if="lesson">
        <page-header :title="`Cancel Lesson #${lesson.id}`">
            <router-link class="btn btn-navy" to="/me/my-lessons"
                >Back to Lessons</router-link
            >
        </page-header>
        <div class="max-w-lg">
            <colour-label
                text="note"
                colour="red"
                class="uppercase text-xs"
            ></colour-label>
            <p class="my-4">
                You are about to cancel this lesson. That means the student will
                still be charged for the time, and should only be done if the
                student failed to appear for the lesson and did not give
                sufficient warning.
            </p>
            <p>
                If the lesson is to be postponed, do not cancel it, just log the
                lesson when it has been done.
            </p>
        </div>

        <form @submit.prevent="submit" class="max-w-lg">
            <div
                class="my-4"
                :class="{ 'border-b border-red-400': formErrors.reason }"
            >
                <label class="form-label" for="reason"
                    >Reason for cancellation</label
                >
                <span class="text-xs text-red-400" v-show="formErrors.reason">{{
                    formErrors.reason
                }}</span>
                <textarea
                    v-model="formData.reason"
                    class="input-text h-32"
                    id="reason"
                ></textarea>
            </div>
            <div class="flex justify-end">
                <router-link to="/me/my-lessons" class="btn mr-4"
                    >Cancel</router-link
                >
                <button
                    type="submit"
                    class="btn btn-red"
                    :disabled="!formData.reason || waiting"
                >
                    Yes, Cancel Lesson
                </button>
            </div>
        </form>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import ColourLabel from "../UI/ColourLabel";
import { notify } from "../Messaging/notify";
import { showValidationErrors } from "../../libs/forms";

export default {
    components: {
        Page,
        PageHeader,
        ColourLabel
    },

    data() {
        return {
            waiting: false,
            formData: {
                reason: ""
            },
            formErrors: {
                reason: ""
            }
        };
    },

    computed: {
        lesson() {
            return this.$store.getters["me/lessonById"](this.$route.params.id);
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchDueLessons").catch(notify.error);
    },

    methods: {
        submit() {
            this.waiting = true;
            this.$store
                .dispatch("me/cancelTeacherLesson", {
                    lesson_id: this.lesson.id,
                    reason: this.formData.reason
                })
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess() {
            notify.success({ message: "Lesson cancelled." });
            this.$router.push("/me/my-lessons");
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = showValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }

            notify.error({ message: "Unable to cancel lesson." });
        }
    }
};
</script>
