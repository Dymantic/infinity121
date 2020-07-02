<template>
    <div v-if="teacher" class="max-w-4xl mx-auto">
        <page-header :title="teacher.name">
            <router-link :to="`/teachers/${teacher.id}/edit`" class="btn mr-4"
                >Edit</router-link
            >
            <router-link to="/teachers" class="btn btn-navy"
                >Back to teachers</router-link
            >
        </page-header>

        <profile-card :profile="teacher" />

        <div
            class="flex justify-between items-center my-12 shadow bg-white p-8"
        >
            <div>
                <p class="uppercase tracking-wide font-bold text-sm">Status</p>
                <p class="mt-4 max-w-sm">
                    A teacher marked as active can have their profile shown on
                    the public site.
                </p>
            </div>
            <div class="flex flex-col items-center">
                <p
                    v-if="teacher.is_public"
                    class="text-sm uppercase tracking-wide font-bold text-green-500"
                >
                    Active
                </p>
                <p
                    v-else
                    class="text-sm uppercase tracking-wide font-bold text-red-500"
                >
                    Inactive
                </p>
                <toggle-switch
                    :disabled="waiting_status"
                    :state="public_status"
                    @toggle="toggleStatus"
                />
            </div>
        </div>

        <div v-if="subjects" class="my-12 shadow bg-white p-8">
            <div class="flex justify-between">
                <p class="uppercase tracking-wide font-bold text-sm">
                    Eligible courses
                </p>
                <set-teacher-courses :teacher="teacher" />
            </div>
            <p v-if="!teacher.subjects.length">
                No courses have been assigned to this teacher yet.
            </p>
            <div v-else>
                <p v-for="subject in teacher.subjects" :key="subject.id">
                    {{ subject.title.en }}
                </p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import ProfileCard from "../Profiles/ProfileCard";
import ToggleSwitch from "../UI/ToggleSwitch";
import SetTeacherCourses from "./SetTeacherCourses";
import { notify } from "../Messaging/notify";

export default {
    components: {
        PageHeader,
        ProfileCard,
        ToggleSwitch,
        SetTeacherCourses
    },

    data() {
        return {
            teacher_id: null,
            waiting_status: false,
            selected_courses: []
        };
    },

    computed: {
        teacher() {
            return this.$store.getters["teachers/byId"](
                parseInt(this.teacher_id)
            );
        },

        subjects() {
            return this.$store.state.subjects.subjects;
        },

        public_status() {
            if (this.waiting_status) {
                return "unset";
            }

            return this.teacher.is_public ? "on" : "off";
        }
    },

    created() {
        this.teacher_id = this.$route.params.id;
        this.$store.dispatch("teachers/fetchTeachers").catch(notify.error);
    },

    watch: {
        $route(to) {
            this.teacher_id = to.params.id;
        }
    },

    methods: {
        toggleStatus() {
            this.waiting_status = true;
            return this.teacher.is_public
                ? this.retractTeacher()
                : this.publishTeacher();
        },

        publishTeacher() {
            this.$store
                .dispatch("teachers/publishTeacher", this.teacher.id)
                .then(() =>
                    notify.success({
                        message: `${
                            this.teacher.name
                        } has been marked as active.`
                    })
                )
                .catch(notify.error)
                .then(() => (this.waiting_status = false));
        },

        retractTeacher() {
            this.$store
                .dispatch("teachers/retractTeacher", this.teacher.id)
                .then(() =>
                    notify.success({
                        message: `${
                            this.teacher.name
                        } has been marked as inactive.`
                    })
                )
                .catch(notify.error)
                .then(() => (this.waiting_status = false));
        }
    }
};
</script>
