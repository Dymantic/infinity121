<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="Edit Teacher Profile"></page-header>

        <edit-form
            :profile="teacher"
            :redirect-on-success="`/teachers/${teacher.id}`"
            v-if="teacher"
        ></edit-form>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import Edit from "./Edit";
import { notify } from "../Messaging/notify";
export default {
    components: {
        PageHeader,
        "edit-form": Edit
    },

    data() {
        return {
            teacher: null
        };
    },

    watch: {
        $route() {
            this.fetchTeacher();
        }
    },

    mounted() {
        this.fetchTeacher();
    },

    methods: {
        fetchTeacher() {
            this.$store
                .dispatch("teachers/fetchTeacherById", this.$route.params.id)
                .then(teacher => (this.teacher = teacher))
                .catch(notify.error);
        }
    }
};
</script>
