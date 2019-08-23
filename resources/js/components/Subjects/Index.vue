<template>
    <div>
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Subjects</h1>
            <div class="flex justify-end items-center">
                <add-subject-button @submission-error="addSubjectError"
                                    @subject-added="subjectAdded"
                ></add-subject-button>
            </div>
        </section>
        <div class="max-w-xl mx-auto p-8 bg-white shadow">
            <div v-for="subject in subjects" :key="subject.id" class="mb-1">
                <router-link class="font-semibold hover:text-indigo-500" :to="`/show/${subject.id}`">{{ subject.title['en'] }}</router-link>
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

        methods: {
            subjectAdded({id, title}) {
                notify.success({message: `${title['en']} has been added`});

                this.$store.dispatch('subjects/fetchSubjects')
                    .catch(notify.error);

                this.$router.push(`/edit/${id}`)
            },

            addSubjectError(message) {
                notify.error(message)
            }
        }
    }
</script>

<style scoped lang="scss" type="text/scss">

</style>