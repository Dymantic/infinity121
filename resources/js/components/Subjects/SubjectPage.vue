<template>
    <div v-if="subject" class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">{{ subject.title.en }}</h1>
            <div class="flex justify-end items-center">
                <router-link class="nav-link active" :to="`/subjects/${subject_id}/show`">View</router-link>
                <router-link class="nav-link" :to="`/subjects/${subject_id}/edit`">Edit</router-link>
                <router-link class="nav-link" :key="$route.fullPath" :to="`/subjects/${subject_id}/delete`">Delete</router-link>
            </div>
        </section>
        <router-view></router-view>
    </div>

</template>

<script type="text/babel">
    export default {
        computed: {
            subject_id() {
                return parseInt(this.$route.params.id);
            },

            subject() {
                return this.$store.getters['subjects/byId'](this.subject_id);
            }
        },

        mounted() {
            if(!this.subject) {
                this.$store.dispatch('subjects/fetchSubjects');
            }
        }
    }
</script>
