<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Subjects</h1>
            <div class="flex justify-end items-center">
                <router-link to="/subjects-order" class="btn mr-4">Set Order</router-link>
                <add-subject-button @submission-error="addSubjectError"
                                    @subject-added="subjectAdded"
                ></add-subject-button>
            </div>
        </section>
        <div class="max-w-4xl mx-auto">

                <router-link v-for="subject in subjects" :key="subject.id" class="font-semibold hover:text-hms-navy" :to="`/subjects/${subject.id}/show`">
                    <div  class="mb-8 max-w-2xl bg-white shadow-lg flex items-center">
                        <div class="grad-bg-indigo w-16 h-12 mr-8">
                            <img v-if="subject.title_image.thumb" :src="subject.title_image.thumb"
                                 class="w-full h-full object-cover"
                                 alt="">
                        </div>
                        <p class="">{{ subject.title.en }}</p>
                    </div>
                </router-link>
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
