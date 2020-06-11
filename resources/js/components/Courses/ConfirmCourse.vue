<template>
    <span>
        <button @click="showForm = true" class="btn btn-navy">Confirm</button>
        <modal :show="showForm" @close="showForm = false">
            <div class="p-6 w-screen max-w-md">
                <p class="text-xl font-bold">Confirm this course?</p>
                <p class="my-6 text-sm">
                    By confirming this course of lessons you acknowledge that
                    the course may begin after the start date. It assumes you
                    are happy with the financial arrangement and that the
                    teacher is prepared to begin the lessons.
                </p>
                <div
                    class="my-4"
                    :class="{
                        'border-b border-red-400': formErrors.starts_from
                    }"
                >
                    <label class="form-label">Course starts from:</label>
                    <span
                        class="text-xs text-red-400"
                        v-show="formErrors.starts_from"
                        >{{ formErrors.starts_from }}</span
                    >
                    <datepicker
                        v-model="formData.starts_from"
                        :inline="true"
                    ></datepicker>
                </div>
                <div class="flex justify-end mt-6">
                    <button @click="showForm = false" class="btn mr-4">
                        Cancel
                    </button>
                    <button @click="confirm" class="btn btn-navy">
                        Confirm
                    </button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
import Datepicker from "vuejs-datepicker";
import { confirmCourse } from "../../api/courses";
import { notify } from "../Messaging/notify";
export default {
    components: {
        Datepicker
    },

    props: ["course"],

    data() {
        return {
            showForm: false,
            formData: {
                starts_from: new Date()
            },
            formErrors: {
                starts_from: ""
            }
        };
    },

    methods: {
        confirm() {
            confirmCourse(this.course.id, this.formData.starts_from)
                .then(() => {
                    this.$emit("updated");
                    notify.success({ message: "Course has been confirmed." });
                })
                .catch(() =>
                    notify.error({ message: "Unable to confirm course" })
                )
                .then(() => (this.showForm = false));
        }
    }
};
</script>
