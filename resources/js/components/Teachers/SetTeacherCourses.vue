<template>
    <span>
        <button type="button" @click="openForm" class="underline font-bold text-gray-600 hover:text-indigo-500">Assign courses</button>
        <modal :show="showForm" @close="closeForm">
            <div class="max-w-lg w-full p-8">
                <p class="uppercase tracking-wide font-bold text-sm">Eligible courses</p>
                <p class="mt-4 max-w-sm mb-6">Select which courses this teacher is able to teach.</p>
                <form action="" @submit.prevent="assignCourses">
                    <div v-for="subject in subjects" :key="subject.id" class="my-1">
                        <input type="checkbox" :value="subject.id" :id="`subject_${subject.id}`" v-model="selected_subjects">
                        <label :for="`subject_${subject.id}`">{{ subject.title.en }}</label>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button :disabled="waiting" type="submit" class="btn btn-indigo">Assign Courses</button>
                    </div>
                </form>
            </div>
        </modal>
    </span>

</template>

<script type="text/babel">
    import Modal from "@dymantic/modal";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            Modal,
        },

        props: ['teacher'],

        data() {
            return {
                showForm: false,
                selected_subjects: [],
                waiting: false,
            };
        },

        computed: {
            subjects() {
                return this.$store.state.subjects.subjects;
            },
        },

        methods: {
            openForm() {
                this.selected_subjects = this.teacher.subjects.map(subject => subject.id);
                this.showForm = true;
            },

            closeForm() {
              this.showForm = false;
            },

            assignCourses() {
                this.waiting = true;
                this.$store.dispatch('teachers/assignCourses', {
                    teacher_id: this.teacher.id,
                    subject_ids: this.selected_subjects
                })
                .then(notify.success)
                .catch(notify.error)
                .then(() => {
                    this.waiting = false;
                    this.closeForm();
                })
            }
        }
    }
</script>
