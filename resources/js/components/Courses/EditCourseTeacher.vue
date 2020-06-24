<template>
    <div v-if="course">
        <p class="text-lg font-bold mb-6">Assign Teacher to Course</p>
        <div class="mb-10">
            <p class="font-bold mb-6">Current Teacher</p>
            <div v-if="course.profile_id">
                <div class="m-4 p-4 shadow">
                    <div class="flex items-center">
                        <img
                            :src="course.teacher_avatar_thumb"
                            class="w-8 h-8 rounded-full mr-4"
                        />
                        <p class="">{{ course.teacher_name }}</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button
                        @click="clearTeacher"
                        class="text-sm font-bold text-gray-600 hover:text-hms-navy"
                    >
                        Clear Teacher
                    </button>
                </div>
            </div>

            <p v-else class="">
                No teacher has been assigned to this course yet.
            </p>
        </div>
        <div v-if="!course.profile_id">
            <p class="font-bold mb-6">Available Teachers</p>
            <div
                v-for="teacher in available_teachers"
                :key="teacher.id"
                class="m-4 shadow p-4 relative"
            >
                <div class="flex items-center">
                    <img
                        :src="teacher.avatar_thumb"
                        class="w-8 h-8 rounded-full mr-4"
                    />
                    <p class="">{{ teacher.name }}</p>
                </div>
                <div class="mt-4" v-show="teacher.unavailable.length > 0">
                    <p class="text-xs font-bold">
                        Note: Not available at these dates/times:
                    </p>
                    <div v-for="period in teacher.unavailable" class="text-xs">
                        <p>
                            {{ period.starts_pretty }} -
                            {{ period.ends_pretty }}
                        </p>
                    </div>
                </div>
                <button
                    @click="assignTeacher(teacher.id)"
                    class="m-4 absolute top-0 right-0 text-sm font-bold text-gray-600 hover:text-hms-navy"
                >
                    Assign
                </button>
            </div>

            <div>
                <button
                    @click="go_manual = !go_manual"
                    class="text-gray-600 hover:text-hms-navy text-xs"
                >
                    {{ manual_button_text }}
                </button>
            </div>
            <teacher-select
                v-show="go_manual"
                class="max-w-md mx-auto my-10"
                @selected="assignTeacher"
            ></teacher-select>
        </div>
    </div>
</template>

<script type="text/babel">
import TeacherSelect from "../Teachers/TeacherSelect";
import { notify } from "../Messaging/notify";

export default {
    components: {
        TeacherSelect
    },

    props: ["course"],

    data() {
        return {
            available_teachers: [],
            go_manual: false
        };
    },

    computed: {
        manual_button_text() {
            return this.go_manual
                ? "Hide teacher list"
                : "Manually select teacher";
        }
    },

    mounted() {
        if (this.course) {
            this.queryTeachers();
        }
    },

    watch: {
        course(to) {
            if (to) {
                this.queryTeachers();
            }
        }
    },

    methods: {
        queryTeachers() {
            this.$store
                .dispatch("teachers/queryAvailableTeachers", {
                    subject_id: this.course.subject_id,
                    area_id: this.course.area_id,
                    lesson_blocks: this.course.lesson_blocks.map(b => ({
                        day_of_week: b.day_of_week,
                        starts: b.starts.replace(":", ""),
                        ends: b.ends.replace(":", "")
                    }))
                })
                .then(teachers => (this.available_teachers = teachers))
                .catch(notify.error);
        },

        assignTeacher(profile_id) {
            this.$store
                .dispatch("customers/assignTeacherToCourse", {
                    profile_id,
                    course_id: this.course.id
                })
                .then(() => {
                    notify.success({ message: "Teacher assigned to course." });
                    this.$emit("course-updated");
                })
                .catch(notify.error);
        },

        clearTeacher() {
            this.$store
                .dispatch("customers/removeCourseTeacher", this.course.id)
                .then(() => this.$emit("course-updated"))
                .catch(notify.error);
        }
    }
};
</script>
