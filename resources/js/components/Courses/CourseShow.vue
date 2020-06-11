<template>
    <page v-if="course">
        <page-header :title="`Course #${course.id}`">
            <router-link class="btn mr-4" :to="`/courses/${course.id}/edit`"
                >Edit</router-link
            >
            <confirm-course
                v-if="course.status === 'unconfirmed'"
                :course="course"
                @updated="fetchCourse"
            ></confirm-course>
        </page-header>

        <div class="flex justify-between">
            <div class="flex-1 mr-6">
                <div>
                    <p class="uppercase text-xs mb-2">Students</p>
                    <div v-for="student in course.students">
                        <span class="text-lg font-bold">{{
                            student.name
                        }}</span>
                        <span class="ml-4 text-gray-600"
                            >({{ student.age }})</span
                        >
                    </div>
                </div>

                <div class="my-6">
                    <p class="uppercase text-xs mb-2">Subject</p>
                    <p class="text-lg font-bold">
                        {{ course.subject_title["en"] }}
                    </p>
                </div>

                <div class="my-6">
                    <p class="uppercase text-xs mb-2">Teacher</p>
                    <div v-if="!course.profile_id">
                        No teacher has been assigned to the course.
                    </div>
                    <div v-else>
                        <div class="flex items-center">
                            <img
                                :src="course.teacher_avatar_thumb"
                                class="h-8 w-8 rounded-full mr-4 border border-hms-navy"
                            />
                            <p class="font-bold">{{ course.teacher_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="my-6">
                    <p class="uppercase text-xs mb-2">Lesson Times</p>
                    <div v-if="!course.lesson_blocks.length">
                        No times have been set for this course.
                    </div>
                    <div v-else>
                        <div v-for="block in course.lesson_blocks">
                            <span class="font-bold text-lg">{{
                                dayOfWeek(block.day_of_week)
                            }}</span>
                            <span class="text-gray-600 ml-4"
                                >{{ block.starts }} - {{ block.ends }}</span
                            >
                        </div>
                    </div>
                </div>

                <div class="my-6">
                    <p class="uppercase text-xs mb-2">Location</p>
                    <div v-if="!course.area">
                        The course has no location set yet.
                    </div>
                    <div v-else>
                        <p class="text-lg font-bold">
                            {{ course.area.country_name }},
                            {{ course.area.region_name }},
                            {{ course.area_name }}
                        </p>
                        <p>
                            {{ course.address }}
                            <a
                                v-if="course.map_link"
                                :href="course.map_link"
                                class="ml-4 text-hms-navy hover:text-blue-500"
                                >(See on map)</a
                            >
                        </p>

                        <p
                            class="my-4 text-sm max-w-md"
                            v-if="course.location_notes"
                        >
                            {{ course.location_notes }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="w-64 ml-6">
                <course-status-card :course="course"></course-status-card>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import ConfirmCourse from "./ConfirmCourse";
import CourseStatusCard from "./CourseStatusCard";
import { notify } from "../Messaging/notify";

export default {
    components: {
        Page,
        PageHeader,
        ConfirmCourse,
        CourseStatusCard
    },

    data() {
        return {
            course: null
        };
    },

    watch: {
        $route() {
            this.fetchCourse();
        }
    },

    mounted() {
        this.fetchCourse();
    },

    methods: {
        fetchCourse() {
            this.$store
                .dispatch("customers/fetchCourse", this.$route.params.id)
                .then(course => (this.course = course))
                .catch(notify.error);
        },

        dayOfWeek(day) {
            const days = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ];

            return days[day];
        }
    }
};
</script>
