<template>
    <page v-if="lesson && ready">
        <page-header title="Lesson Log">
            <router-link
                class="btn btn-red"
                :to="`/lessons/${lesson.id}/cancel`"
                >Cancel Lesson</router-link
            >
        </page-header>
        <div class="p-4 shadow">
            <p class="uppercase text-sm mb-4">Scheduled Lesson</p>
            <div class="flex justify-between">
                <div>
                    <p class="uppercase text-xs text-gray-600">Student(s)</p>
                    <p v-for="student in lesson.students">
                        <span class="font-bold">{{ student.name }}</span>
                        <span> ({{ student.age }})</span>
                    </p>
                </div>
                <div>
                    <p class="uppercase text-xs text-gray-600">Time</p>
                    <p class="font-bold">{{ lesson.lesson_date_pretty }}</p>
                    <p>{{ lesson.starts }} - {{ lesson.ends }}</p>
                    <p class="uppercase text-sm">{{ lesson.lesson_day }}</p>
                </div>
                <div>
                    <p class="uppercase text-xs text-gray-600">Location</p>
                    <p class="">
                        {{ lesson.location_address }}
                        <span v-if="lesson.location_map">
                            <a
                                :href="lesson.location_map"
                                class="text-xs text-blue-600 hover:underline"
                                target="_blank"
                                >(see map)</a
                            >
                        </span>
                    </p>
                    <p class="">
                        {{ lesson.location_area }}
                    </p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="my-12 max-w-lg">
                <p class="font-bold text-xl">Lesson date and times:</p>
                <p class="text-sm my-4">
                    Please enter the actual date and times the lesson was
                    conducted. Times should be in the format of hh:mm, and in
                    24hr time.
                </p>
                <div class="my-6">
                    <p class="form-label">Lesson Date</p>
                    <span
                        v-if="formErrors.completed_on"
                        class="text-xs text-red-500"
                        >{{ formErrors.completed_on }}
                    </span>

                    <datepicker
                        class="p-2 border w-48"
                        v-model="formData.completed_on"
                    ></datepicker>
                </div>
                <div class="flex my-6">
                    <div
                        class="mr-6"
                        :class="{
                            'border-b border-red-400': formErrors.actual_start
                        }"
                    >
                        <label class="form-label" for="actual_start"
                            >Started at:</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.actual_start"
                            >{{ formErrors.actual_start }}</span
                        >
                        <input
                            type="text"
                            name="actual_start"
                            v-model="formData.actual_start"
                            class="input-text"
                            id="actual_start"
                            placeholder="hh:mm"
                        />
                    </div>
                    <div
                        class=""
                        :class="{
                            'border-b border-red-400': formErrors.actual_end
                        }"
                    >
                        <label class="form-label" for="actual_end"
                            >Finished at:</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.actual_end"
                            >{{ formErrors.actual_end }}</span
                        >
                        <input
                            type="text"
                            name="actual_end"
                            v-model="formData.actual_end"
                            class="input-text"
                            id="actual_end"
                            placeholder="hh:mm"
                        />
                    </div>
                </div>
            </div>
            <div class="my-12 max-w-xl">
                <p class="font-bold text-xl">Teacher's notes and logs</p>
                <p class="text-sm my-4">
                    Please take note of any significant events, what material
                    was covered, the student's participation and how the lesson
                    went for you.
                </p>
                <div
                    class="my-4"
                    :class="{
                        'border-b border-red-400': formErrors.material_taught
                    }"
                >
                    <label class="font-bold" for="material_taught"
                        >Material covered and Homework</label
                    >
                    <p class="text-sm">
                        Briefly describe the material covered in the lesson, and
                        outline the homework you set for the student
                    </p>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.material_taught"
                        >{{ formErrors.material_taught }}</span
                    >
                    <textarea
                        name="material_taught"
                        v-model="formData.material_taught"
                        class="input-text h-32"
                        id="material_taught"
                    ></textarea>
                </div>
                <div
                    class="my-4"
                    :class="{
                        'border-b border-red-400': formErrors.student_report
                    }"
                >
                    <span class="font-bold">Student Report</span>
                    <p class="text-sm">
                        What was the student's participation and attitude
                        towards the lesson like?
                    </p>
                    <student-report
                        v-model="formData.student_report"
                    ></student-report>
                </div>

                <div
                    class="my-4"
                    :class="{
                        'border-b border-red-400': formErrors.teacher_log
                    }"
                >
                    <label class="font-bold" for="teacher_log"
                        >Teacher's log</label
                    >
                    <p class="text-sm">
                        How did the lesson go for you? Please take note of
                        anything that may need to be remembered or explained at
                        a later stage. <strong>Min 20 words</strong>
                    </p>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.teacher_log"
                        >{{ formErrors.teacher_log }}</span
                    >
                    <textarea
                        name="teacher_log"
                        v-model="formData.teacher_log"
                        class="input-text h-32 mb-1"
                        id="teacher_log"
                    ></textarea>
                    <span
                        class="px-2 py-1 text-sm rounded-lg mt-1 border"
                        :class="word_count_colours"
                    >
                        {{ log_word_count }} words
                    </span>
                </div>
            </div>
            <div class="flex max-w-lg justify-end my-12">
                <button
                    @click="$router.push('/me/my-lessons')"
                    type="button"
                    class="btn mr-4"
                >
                    Cancel
                </button>
                <button type="submit" class="btn btn-navy">Submit</button>
            </div>
        </form>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import StudentReport from "./StudentReport";
import Datepicker from "vuejs-datepicker";
import { notify } from "../Messaging/notify";
import { showValidationErrors } from "../../libs/forms";
export default {
    components: {
        Page,
        PageHeader,
        Datepicker,
        StudentReport
    },

    data() {
        return {
            ready: false,
            formData: {
                completed_on: new Date(),
                actual_start: "",
                actual_end: "",
                teacher_log: "",
                student_report: {
                    interaction: "",
                    confidence: "",
                    comprehension: "",
                    output: ""
                },
                material_taught: ""
            },
            formErrors: {
                completed_on: "",
                actual_start: "",
                actual_end: "",
                teacher_log: "",
                student_report: "",
                material_taught: ""
            }
        };
    },

    computed: {
        lesson() {
            return this.$store.getters["me/lessonById"](this.$route.params.id);
        },

        log_word_count() {
            const matches = this.formData.teacher_log.match(/[\w\d’'-]+/gi);
            return matches ? matches.length : 0;
        },

        word_count_colours() {
            return this.log_word_count < 20
                ? "bg-red-200 border-red-500 text-red-700"
                : "bg-green-200 border-green-500 text-green-700";
        }
    },

    watch: {
        lesson(to) {
            this.ready = false;
            if (to) {
                this.setForm(to);
            }
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchDueLessons").catch(notify.error);
    },

    methods: {
        setForm({ lesson_date, starts, ends }) {
            this.formData = {
                completed_on: new Date(lesson_date),
                actual_start: starts,
                actual_end: ends,
                teacher_log: "",
                student_report: {
                    interaction: "",
                    confidence: "",
                    comprehension: "",
                    output: ""
                },
                material_taught: ""
            };
            this.ready = true;
        },

        submit() {
            if (!this.studentReportIsValid()) {
                notify.warn({ message: "Please complete the student report" });
                return;
            }

            if (this.log_word_count < 20) {
                notify.warn({
                    message:
                        "Please fully complete the teacher log. You need more than 20 words."
                });
                return;
            }

            const fd = {
                completed_on: this.formData.completed_on,
                actual_start: this.formData.actual_start,
                actual_end: this.formData.actual_end,
                teacher_log: this.formData.teacher_log,
                material_taught: this.formData.material_taught,
                student_interaction: this.formData.student_report.interaction,
                student_comprehension: this.formData.student_report
                    .comprehension,
                student_confidence: this.formData.student_report.confidence,
                student_output: this.formData.student_report.output
            };
            this.$store
                .dispatch("me/logTeachersLesson", {
                    lesson_id: this.lesson.id,
                    formData: fd
                })
                .then(() => {
                    notify.success({
                        message: "Lesson logged and marked as complete."
                    });
                    this.$router.push("/me/my-lessons");
                })
                .catch(this.onError);
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = showValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            notify.error({ message: "Unable to log lesson." });
        },

        studentReportIsValid() {
            const accepted = ["poor", "okay", "good", "excellent"];

            return (
                accepted.includes(this.formData.student_report.interaction) &&
                accepted.includes(this.formData.student_report.comprehension) &&
                accepted.includes(this.formData.student_report.confidence) &&
                accepted.includes(this.formData.student_report.output)
            );
        }
    }
};
</script>
