<template>
    <span>
        <button class="btn btn-navy" @click="showAddForm = true">Add Subject</button>
        <modal :show="showAddForm">
            <div class="max-w-lg p-8">
                <form action="" @submit.prevent="submitForm">
                    <p class="font-bold text-xl">Add a new teaching subject</p>
                    <p class="mt-2 text-gray-600 text-sm">Give an English title for the subject to get started.</p>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.title}">
                        <label class="form-label" for="title">Title</label>
                        <span class="text-xs text-red-400" v-show="formErrors.title">{{ formErrors.title }}</span>
                        <input type="text" name="title" v-model="formData.title" class="input-text" id="title">
                    </div>
                    <div class="flex justify-end items-center mt-8">
                        <button type="button" @click="showAddForm = false">Cancel</button>
                        <button class="btn btn-navy ml-4" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                showAddForm: false,
                formErrors: {
                    title: '',
                },
                formData: {
                    title: '',
                }
            };
        },

        methods: {
            submitForm() {
                this.$store.dispatch('subjects/addSubject', this.formData.title)
                    .then(this.subjectCreated)
                    .catch(this.submisionFailed);
            },

            subjectCreated(data) {
                this.formData.title = '';
                this.showAddForm = false;
                this.$emit('subject-added', data)
            },

            inputInvalid({errors}) {
                this.formErrors.title = errors.title[0];
            },

            submisionFailed({status, data}) {
                if(status === 422) {
                    return this.inputInvalid(data);
                }

                this.showAddForm = false;
                this.$emit('submission-error', {message: 'Unable to create new subject.'});
            }
        }
    }
</script>

<style scoped lang="scss" type="text/scss">

</style>
