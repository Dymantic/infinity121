<template>
    <div v-if="subject">
        <div>
            <p class="text-lg font-bold text-red-600">Delete {{ subject.title['en'] }}: Are you sure?</p>
            <p class="max-w-md my-6">By deleting this subject, you will remove all record of it. Make sure this is what you want to do before you confirm.</p>
            <button @click="deleteSubject" :disabled="waiting" class="btn btn-red">Yes, delete this course.</button>
        </div>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {

        data() {
            return {
                waiting: false,
            };
        },

        computed: {
            subject() {
                return this.$store.getters['subjects/byId'](this.subject_id);
            },

            subject_id() {
                return parseInt(this.$route.params.id);
            }
        },

        methods: {
            deleteSubject() {
                this.waiting = true;
                const name = this.subject.title['en'];
                this.$store.dispatch('subjects/deleteSubject', this.subject_id)
                .then(() => {
                    this.$router.push("/subjects");
                    notify.success({message: `${name} has been deleted.`})
                })
                .catch(notify.error)
                .then(() => this.waiting = false);
            }
        }


    }
</script>
