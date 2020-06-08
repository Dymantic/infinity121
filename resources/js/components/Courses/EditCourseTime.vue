<template>
    <div>
        <p class="text-lg font-bold mb-6">Course Lesson Times</p>
        <p class="max-w-md my-6 text-sm">
            Enter the day of the week, and times for each lesson. Please ensure
            you enter the times in 24 hour format, such as 17:00 or 14:30
        </p>
        <p v-if="formErrors.lesson_blocks" class="text-xs text-red-500 my-1">
            {{ formErrors.lesson_blocks }}
        </p>
        <div
            v-for="(block, index) in formData.lesson_blocks"
            :key="index"
            class="flex items-center mb-4"
        >
            <div>
                <label
                    :for="`dow_select${index}`"
                    class="form-label text-xs font-bold"
                    >Day of Week</label
                >
                <select
                    v-model="block.day_of_week"
                    :id="`dow_select${index}`"
                    class="input-text w-auto pr-4 mr-4"
                >
                    <option :value="0">Sunday</option>
                    <option :value="1">Monday</option>
                    <option :value="2">Tuesday</option>
                    <option :value="3">Wednesday</option>
                    <option :value="4">Thursday</option>
                    <option :value="5">Friday</option>
                    <option :value="6">Saturday</option>
                </select>
            </div>
            <div class="mx-4">
                <label
                    :for="`start_${index}`"
                    class="form-label font-bold text-xs"
                    >From</label
                >
                <input
                    class="input-text w-24"
                    type="text"
                    placeholder="hh:mm"
                    v-model="block.starts"
                    :id="`start_${index}`"
                />
            </div>
            <div>
                <label
                    :for="`end_${index}`"
                    class="form-label font-bold text-xs"
                    >To</label
                >
                <input
                    class="input-text w-24"
                    type="text"
                    placeholder="hh:mm"
                    v-model="block.ends"
                    :id="`end_${index}`"
                />
            </div>
            <div class="ml-8">
                <button
                    class="font-bold text-sm text-gray-600 hover:text-red-500 pt-8"
                    @click="removeBlock(index)"
                >
                    Remove
                </button>
            </div>
        </div>
        <div class="max-w-sm flex justify-end my-4">
            <button class="text-sm font-bold" @click="addTimeBlock">
                Add new time
            </button>
        </div>
        <div class="my-8 flex justify-end">
            <button class="btn btn-navy" @click="save">Save</button>
        </div>
    </div>
</template>

<script type="text/babel">
import { notify } from "../Messaging/notify";

export default {
    props: ["course"],

    data() {
        return {
            formData: {
                lesson_blocks: [{ day_of_week: null, starts: null, ends: null }]
            },
            formErrors: {
                lesson_blocks: ""
            }
        };
    },

    mounted() {
        if (this.course) {
            this.setData();
        }
    },

    watch: {
        course() {
            this.setData();
        }
    },

    methods: {
        addTimeBlock() {
            this.formData.lesson_blocks.push({
                day_of_week: null,
                starts: null,
                ends: null
            });
        },

        removeBlock(index) {
            this.formData.lesson_blocks.splice(index, 1);
        },

        setData() {
            this.formData.lesson_blocks = [...this.course.lesson_blocks];
            if (this.formData.lesson_blocks.length === 0) {
                this.addTimeBlock();
            }
        },

        save() {
            this.formErrors = { lesson_blocks: "" };
            const data = { ...this.formData };

            this.$store
                .dispatch("customers/updateCourseTimes", {
                    course_id: this.course.id,
                    formData: {
                        blocks: data.lesson_blocks.filter(
                            b => b.day_of_week && b.starts && b.ends
                        )
                    }
                })
                .then(() => {
                    notify.success({ message: "Lesson times updated." });
                    this.$emit("updated");
                })
                .catch(this.onError);
        },

        onError({ status, data }) {
            if (status === 422) {
                this.formErrors.lesson_blocks =
                    "Please ensure that your lesson times are in the correct format (hh:mm) in 24hr time,and that the start time is before the end time.";
            }
        }
    }
};
</script>
