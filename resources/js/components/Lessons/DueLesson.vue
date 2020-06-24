<template>
    <div class="flex shadow my-6">
        <div class="p-4 bg-gray-100">
            <p class="font-bold">{{ lesson.lesson_date_pretty }}</p>
            <p>{{ lesson.starts }} - {{ lesson.ends }}</p>
            <p class="uppercase text-sm">{{ lesson.lesson_day }}</p>
        </div>
        <div class="ml-4 p-4">
            <p class="font-bold text-hms-navy">
                {{ studentNames(lesson.students) }}
            </p>
            <p class="text-sm">
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
            <p class="uppercase text-xs">
                {{ lesson.location_area }}
            </p>
        </div>
        <div class="p-4 ml-4">
            <p>
                Lesson
                <strong>{{ lesson.lesson_number }}</strong> of
                <strong>{{ lesson.course_total_lessons }}</strong>
            </p>
        </div>
        <div class="p-4 ml-4">
            <router-link
                class="font-bold text-blue-600 hover:underline"
                :to="`/lessons/${lesson.id}/log`"
                >Log Lesson</router-link
            >
        </div>
    </div>
</template>

<script type="text/babel">
import { listStudentNames } from "../../libs/formatters";

export default {
    props: ["lesson"],

    methods: {
        studentNames(students) {
            return listStudentNames(students);
        }
    }
};
</script>
