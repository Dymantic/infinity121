<template>
    <div>
        <p class="text-lg font-bold mb-6">Course General Info</p>
        <div class="mb-6">
            <label class="form-label font-bold" for="start_date"
                >Course starts from:</label
            >
            <span
                class="text-xs text-red-500 my-1"
                v-if="formErrors.starts_from"
                >{{ formErrors.starts_from }}</span
            >
            <div class="flex mt-1">
                <datepicker
                    v-model="formData.starts_from"
                    class="border rounded p-1 bg-white"
                    id="start_date"
                ></datepicker>
            </div>
        </div>
        <div>
            <span class="form-label font-bold">Students</span>
            <p class="text-xs text-red-500 my-1" v-if="formErrors.students">
                {{ formErrors.students }}
            </p>
            <div
                v-for="(student, index) in formData.students"
                class="flex pl-4 mb-2"
            >
                <div>
                    <label
                        class="form-label text-xs"
                        :for="`sname_${student.name}_${index}`"
                        >Name</label
                    >
                    <input
                        type="text"
                        :id="`sname_${student.name}_${index}`"
                        v-model="student.name"
                        class="input-text"
                    />
                </div>
                <div class="ml-6">
                    <label
                        class="form-label text-xs"
                        :for="`age_${student.age}_${index}`"
                        >Age</label
                    >
                    <input
                        type="text"
                        :id="`age_${student.age}_${index}`"
                        v-model="student.age"
                        class="input-text w-20"
                    />
                </div>
            </div>
            <div class="max-w-sm mt-4 flex justify-end">
                <button
                    @click="formData.students.push({ name: '', age: '' })"
                    class="text-xs font-bold"
                >
                    Add student
                </button>
            </div>
        </div>
        <div
            class="my-6 flex items-center"
            :class="{ 'border-b border-red-400': formErrors.total_lessons }"
        >
            <label class="form-label mb-0 font-bold" for="total_lessons"
                >Total Lessons</label
            >

            <input
                type="text"
                name="total_lessons"
                v-model="formData.total_lessons"
                class="input-text w-16 mt-0 ml-4"
                id="total_lessons"
            />
            <span
                class="text-xs text-red-500 my-1"
                v-show="formErrors.total_lessons"
                >{{ formErrors.total_lessons }}</span
            >
        </div>
        <div class="my-6">
            <label class="form-label font-bold" for="subject_select">
                Subject
            </label>
            <span
                class="text-xs text-red-500 my-1"
                v-show="formErrors.subject_id"
                >{{ formErrors.subject_id }}</span
            >
            <select
                name="subject_id"
                id="subject_select"
                v-model="formData.subject_id"
                class="input-text w-auto ml-4 pr-4"
            >
                <option
                    v-for="subject in subjects"
                    :key="subject.id"
                    :value="subject.id"
                    >{{ subject.title["en"] }}</option
                >
            </select>
        </div>
        <div class="my-8 flex justify-end">
            <button @click="save" class="btn btn-navy mr-4">Save</button>
            <button @click="proceed" class="btn">Next</button>
        </div>
    </div>
</template>

<script type="text/babel">
import Datepicker from "vuejs-datepicker";
import { notify } from "../Messaging/notify";

export default {
    components: {
        Datepicker
    },

    props: ["customer", "course"],

    data() {
        return {
            formData: {
                students: [{ name: "", age: "" }],
                total_lessons: 20,
                subject_id: null,
                starts_from: new Date()
            },
            formErrors: {
                students: "",
                total_lessons: "",
                subject_id: "",
                starts_from: ""
            }
        };
    },

    computed: {
        subjects() {
            return this.$store.state.subjects.subjects;
        }
    },

    watch: {
        course() {
            this.setData();
        }
    },

    mounted() {
        if (this.course) {
            this.setData();
        }
    },

    methods: {
        setData() {
            this.formData = {
                students: this.course.students,
                total_lessons: this.course.total_lessons,
                starts_from: new Date(this.course.starts_from),
                subject_id: this.course.subject_id
            };
        },

        save() {
            this.clearValidationErrors();
            const data = { ...this.formData };
            data.students = this.formData.students.filter(s => s.name && s.age);

            this.course ? this.updateCourse(data) : this.createCourse(data);
        },

        createCourse(data) {
            this.$store
                .dispatch("customers/createCustomerCourse", {
                    customer_id: this.customer.id,
                    formData: data
                })
                .then(course => {
                    notify.success({ message: "Course created." });
                    this.$emit("created", course.id);
                })
                .catch(this.onSaveError);
        },

        onSaveError({ status, data }) {
            if (status === 422) {
                return this.setValidationErrors(data.errors);
            }

            notify.error({ message: "Unable to save course info." });
        },

        updateCourse(data) {
            this.$store
                .dispatch("customers/updateCourseBasic", {
                    course_id: this.course.id,
                    formData: data
                })
                .then(() => {
                    notify.success({ message: "Course info updated." });
                    this.$emit("updated");
                })
                .catch(this.onSaveError);
        },

        setValidationErrors(errors) {
            Object.keys(errors).forEach(key => {
                if (key.includes("student")) {
                    this.formErrors.students =
                        "Please ensure all students have a name and age.";
                }
                if (key === "starts_from") {
                    this.formErrors.starts_from =
                        "Please ensure the starting date is correct.";
                }
                if (key === "total_lessons") {
                    this.formErrors.total_lessons =
                        "Total lessons requires a number.";
                }
                if (key === "subject_id") {
                    this.formErrors.subject_id =
                        "You need to select a valid subject";
                }
            });
        },

        clearValidationErrors() {
            this.formErrors = {
                students: "",
                total_lessons: "",
                subject_id: "",
                starts_from: ""
            };
        },

        proceed() {
            this.$emit("to-next");
        }
    }
};
</script>
