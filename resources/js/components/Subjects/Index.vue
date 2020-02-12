<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Subjects</h1>
            <div class="flex justify-end items-center">
                <add-subject-button @submission-error="addSubjectError"
                                    @subject-added="subjectAdded"
                ></add-subject-button>
            </div>
        </section>
        <div class="max-w-4xl mx-auto three-grid">
            <div v-for="subject in subjects" :key="subject.id" class="mb-8 w-64 mx-auto bg-white shadow-lg">
                <router-link class="font-semibold hover:text-hms-navy" :to="`/subjects/${subject.id}/show`">
                    <div class="pb-3/4 grad-bg-indigo relative w-full">
                        <img v-if="subject.title_image.thumb" :src="subject.title_image.thumb"
                             class="w-full h-full object-cover absolute"
                             alt="">
                    </div>

                    <p class="px-4 pb-4 pt-2">{{ subject.title.en }}</p>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import AddSubjectButton from "./AddSubjectButton";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            AddSubjectButton,
        },

        computed: {
            subjects() {
                return this.$store.getters['subjects/sorted_subjects'];
            }
        },

        mounted() {
            this.$store.dispatch('subjects/fetchSubjects').catch(notify.error);
        },

        methods: {
            subjectAdded({id, title}) {
                notify.success({message: `${title['en']} has been added`});

                this.$store.dispatch('subjects/fetchSubjects')
                    .catch(notify.error);

                this.$router.push(`/subjects/${id}/edit`)
            },

            addSubjectError(message) {
                notify.error(message)
            }
        }
    }
</script>

<style scoped lang="scss" type="text/scss">

</style>
