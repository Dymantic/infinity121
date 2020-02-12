<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Your user info</h1>
            <div class="flex justify-end items-center">

            </div>
        </section>
        <div class="p-8 max-w-lg shadow-lg">
            <p><strong>Note: </strong>This is just your private user info, and not shared publicly on the site. If you want to change your public info, you can <router-link to="/me/show" class="hover:underline text-hms-navy">edit your profile</router-link>.</p>
            <p class="mt-4">If you need to reset your password, you can <router-link class="hover:underline text-hms-navy" to="/me/password/edit">do that here.</router-link></p>
        </div>
        <form v-if="form_ready" @submit.prevent="submit" class="p-8 max-w-lg shadow-lg">
            <div class="my-4" :class="{'border-b border-red-400': formErrors.name}">
                <label class="form-label" for="name">Name</label>
                <span class="text-xs text-red-400" v-show="formErrors.name">{{ formErrors.name }}</span>
                <input type="text" name="name" v-model="formData.name" class="input-text" id="name">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.email}">
                <label class="form-label" for="email">Email</label>
                <span class="text-xs text-red-400" v-show="formErrors.email">{{ formErrors.email }}</span>
                <input type="email" name="email" v-model="formData.email" class="input-text" id="email">
            </div>
            <button :disbaled="waiting" type="submit" class="btn btn-navy mt-6">Update your details</button>
        </form>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {
        data() {
            return {
                waiting: false,
                form_ready: false,
                formData: {},
                formErrors: {
                    name: '',
                    email: ''
                },
            };
        },

        computed: {
          user() {
              return this.$store.getters['me/details'];
          }
        },

        mounted() {
            if(!this.user.name) {
                return this.$store.dispatch('me/fetchCurrentUser')
                    .then(() => this.setForm())
                    .catch(notify.error);
            }

            this.setForm();
        },

        methods: {
            setForm() {
                if(this.user.name) {
                    this.formData.name = this.user.name;
                    this.formData.email = this.user.email;
                    this.form_ready = true;
                }
            },

            submit() {
                this.waiting = true;
                this.$store.dispatch('me/updateCurrentUser', this.formData)
                    .then(() => {
                        notify.success({message: 'Your info has been updated'});
                        this.$router.push('/me/show');
                    })
                    .catch(({status, data}) => {
                        if(status === 422) {
                            return this.setValidationMessages(data.errors);
                        }
                        notify.errors({message: 'Unable to update the current user'});
                    })
                    .then(() => this.waiting = false);
            },

            setValidationMessages(errors) {
                if(errors.name) {
                    this.formErrors.name = errors.name[0];
                }

                if(errors.email) {
                    this.formErrors.email = errors.email[0];
                }
            }
        }


    }
</script>
