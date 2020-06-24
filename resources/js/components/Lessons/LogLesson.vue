<template>
    <page v-if="lesson && ready">
        <page-header title="Lesson Log"></page-header>
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
            <div class="my-12 max-w-md">
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
            <div class="my-12 max-w-md">
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
                    <label class="form-label" for="material_taught"
                        >Material covered</label
                    >
                    <p class="text-sm">
                        Briefly describe the material covered in the lesson
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
                    <label class="form-label" for="student_report"
                        >Student Report</label
                    >
                    <p class="text-sm">
                        What was the student's participation and attitude
                        towards the lesson like?
                    </p>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.student_report"
                        >{{ formErrors.student_report }}</span
                    >
                    <textarea
                        name="student_report"
                        v-model="formData.student_report"
                        class="input-text h-32"
                        id="student_report"
                    ></textarea>
                </div>
                <div
                    class="my-4"
                    :class="{
                        'border-b border-red-400': formErrors.teacher_log
                    }"
                >
                    <label class="form-label" for="teacher_log"
                        >Teacher's log</label
                    >
                    <p class="text-sm">
                        How did the lesson do for you? Please take note of
                        anything that may need to be remembered or explained at
                        a later stage.
                    </p>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.teacher_log"
                        >{{ formErrors.teacher_log }}</span
                    >
                    <textarea
                        name="teacher_log"
                        v-model="formData.teacher_log"
                        class="input-text h-32"
                        id="teacher_log"
                    ></textarea>
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
import Datepicker from "vuejs-datepicker";
import { notify } from "../Messaging/notify";
import { showValidationErrors } from "../../libs/forms";
export default {
    components: {
        Page,
        PageHeader,
        Datepicker
    },

    data() {
        return {
            ready: false,
            formData: {
                completed_on: new Date(),
                actual_start: "",
                actual_end: "",
                teacher_log: "",
                student_report: "",
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
                student_report: "",
                material_taught: ""
            };
            this.ready = true;
        },

        submit() {
            this.$store
                .dispatch("me/logTeachersLesson", {
                    lesson_id: this.lesson.id,
                    formData: this.formData
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
        }
    }
};
</script>
