<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">Reset Your Password</h1>
            <div class="flex justify-end items-center">

            </div>
        </section>
        <div class="max-w-md">
            <form @submit.prevent="submit">
                <div class="my-4" :class="{'border-b border-red-400': formErrors.old_password}">
                    <label class="form-label" for="old_password">Current Password</label>
                    <span class="text-xs text-red-400"
                          v-show="formErrors.old_password">{{ formErrors.old_password }}</span>
                    <input type="password" name="old_password" v-model="formData.old_password" class="input-text"
                           id="old_password">
                </div>
                <div class="my-4" :class="{'border-b border-red-400': formErrors.password}">
                    <label class="form-label" for="password">Password</label>
                    <span class="text-xs text-red-400" v-show="formErrors.password">{{ formErrors.password }}</span>
                    <input type="password" name="password" v-model="formData.password" class="input-text" id="password">
                </div>
                <div class="my-4" :class="{'border-b border-red-400': formErrors.password_confirmation}">
                    <label class="form-label" for="password_confirmation">Confirm Your Password</label>
                    <span class="text-xs text-red-400" v-show="formErrors.password_confirmation">{{ formErrors.password_confirmation }}</span>
                    <input type="password" name="password_confirmation" v-model="formData.password_confirmation"
                           class="input-text" id="password_confirmation">
                </div>
                <button class="btn" type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {
        data() {
            return {
                formData: {
                    old_password: '',
                    password: '',
                    password_confirmation: '',
                },
                formErrors: {
                    old_password: '',
                    password: '',
                }
            };
        },

        methods: {
            submit() {
                this.$store.dispatch('me/resetUserPassword', {
                    old_password: this.formData.old_password,
                    new_password: this.formData.password,
                    new_password_confirmation: this.formData.password_confirmation,
                })
                    .then(mess => this.onPasswordReset(mess))
                    .catch(({status, data}) => {
                        if (status === 422) {
                            this.formErrors.old_password = data.errors.old_password ? data.errors.old_password[0] : "";
                            this.formErrors.password = data.errors.new_password ? data.errors.new_password[0] : "";
                            return;
                        }
                        notify.error({message: 'Unable to reset password.'});
                    });
            },

            onPasswordReset(message) {
                notify.success(message);
                this.$router.push('/me/show');
            }
        }
    }
</script>
