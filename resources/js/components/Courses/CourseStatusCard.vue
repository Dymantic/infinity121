<template>
    <div class="p-6 shadow">
        <div>
            <div>
                <p class="uppercase text-xs">Status</p>
                <course-status-badge
                    :status="course.status"
                ></course-status-badge>
            </div>
            <p class="my-6 text-sm" v-if="course.status === 'unconfirmed'">
                This course has not been confirmed yet. Once you are happy that
                the financial arrangements are settled, and the teacher is
                prepared to begin lessons, you may confirm and get the ball
                rolling.
            </p>
            <p class="my-6 text-sm" v-if="course.status === 'confirmed'">
                This course has been confirmed and lessons should begin after
                <span class="font-bold">{{ course.starts_from_pretty }}</span>
            </p>
            <p class="my-6 text-sm" v-if="course.status === 'ongoing'">
                This course is currently underway, and so far
                <span class="font-bold">{{ course.lessons.length }}</span> of
                <strong>{{ course.total_lessons }}</strong> lessons have been
                completed.
            </p>
        </div>
    </div>
</template>

<script type="text/babel">
import CourseStatusBadge from "./CourseStatusBadge";
export default {
    props: ["course"],

    components: {
        CourseStatusBadge
    }
};
</script>
