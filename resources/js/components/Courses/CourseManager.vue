<template>
    <div class="flex justify-between">
        <div class="w-64 mr-4">
            <div class="flex items-center mb-2">
                <button
                    class="font-bold text-gray-600 hover:text-hms-navy focus:outline-none"
                    @click="section = 'basic'"
                >
                    General Info
                </button>
                <div
                    class="w-3 h-3 rounded-full bg-hms-navy ml-2"
                    v-show="section === 'basic'"
                ></div>
            </div>

            <div class="flex items-center mb-2">
                <button
                    class="font-bold text-gray-600 hover:text-hms-navy focus:outline-none"
                    @click="section = 'time'"
                    :disabled="!canViewTimes"
                >
                    Time Info
                </button>
                <div
                    class="w-3 h-3 rounded-full bg-hms-navy ml-2"
                    v-show="section === 'time'"
                ></div>
            </div>

            <div class="flex items-center mb-2">
                <button
                    class="font-bold text-gray-600 hover:text-hms-navy focus:outline-none"
                    @click="section = 'location'"
                    :disabled="!canViewLocation"
                >
                    Location Info
                </button>
                <div
                    class="w-3 h-3 rounded-full bg-hms-navy ml-2"
                    v-show="section === 'location'"
                ></div>
            </div>

            <div class="flex items-center mb-2">
                <button
                    class="font-bold text-gray-600 hover:text-hms-navy focus:outline-none"
                    @click="section = 'teacher'"
                    :disabled="!canViewTeacher"
                >
                    Teacher
                </button>
                <div
                    class="w-3 h-3 rounded-full bg-hms-navy ml-2"
                    v-show="section === 'teacher'"
                ></div>
            </div>
        </div>
        <div class="flex-1">
            <edit-course-basic
                v-show="section === 'basic'"
                :customer="customer"
                :course="course"
                @created="courseCreated"
                @updated="$emit('updated')"
                @to-next="section = 'time'"
            ></edit-course-basic>
            <edit-course-time
                v-show="section === 'time'"
                :course="course"
                @updated="$emit('updated')"
            ></edit-course-time>
            <edit-course-location
                v-show="section === 'location'"
                :course="course"
                @updated="$emit('updated')"
            ></edit-course-location>
            <edit-course-teacher
                :course="course"
                v-show="section === 'teacher'"
                @course-updated="$emit('updated')"
            ></edit-course-teacher>
        </div>
    </div>
</template>

<script type="text/babel">
import EditCourseBasic from "./EditCourseBasic";
import EditCourseTime from "./EditCourseTime";
import EditCourseTeacher from "./EditCourseTeacher";
import EditCourseLocation from "./EditCourseLocation";
import { notify } from "../Messaging/notify";
export default {
    props: ["course", "customer"],

    components: {
        EditCourseBasic,
        EditCourseTime,
        EditCourseTeacher,
        EditCourseLocation
    },

    data() {
        return {
            section: "basic"
        };
    },

    computed: {
        canViewTimes() {
            return this.course;
        },

        canViewLocation() {
            return this.course;
        },

        canViewTeacher() {
            return (
                this.course &&
                this.course.subject_id &&
                this.course.area_id &&
                this.course.lesson_blocks.length
            );
        }
    },

    mounted() {
        this.$store.dispatch("subjects/fetchSubjects").catch(notify.error);
        this.$store.dispatch("locations/fetchLocations").catch(notify.error);
    },

    methods: {
        courseCreated(id) {
            this.$emit("course-created", id);
        }
    }
};
</script>
