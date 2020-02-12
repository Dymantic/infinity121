<template>
    <modal :show="showForm"
           @close="$emit('close')">
        <div class="p-8">
            <p class="text-3xl mb-8">Add a New Teacher</p>
            <vue-form url="/admin/api/users/teachers"
                      :form-model="form"
                      @submission-failed="failedToSubmit"
                      @submission-okay="teacherAdded">
                <div slot-scope="{formData, formErrors, waiting}">
                    <div class="my-4"
                         :class="{'border-b border-red-400': formErrors.name}">
                        <label class="form-label"
                               for="">Name</label>
                        <span class="text-xs text-red"
                              v-show="formErrors.name">{{ formErrors.name }}</span>
                        <input type="text"
                               name="name"
                               v-model="formData.name"
                               class="input-text"
                               id="name">
                    </div>
                    <div class="my-4"
                         :class="{'border-b border-red-400': formErrors.email}">
                        <label class="form-label"
                               for="email">Email</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.email">{{ formErrors.email }}</span>
                        <input type="text"
                               name="email"
                               v-model="formData.email"
                               class="input-text"
                               id="email">
                    </div>

                    <div class="my-4"
                         :class="{'border-b border-red-400': formErrors.password}">
                        <label class="form-label"
                               for="password">Password</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.password">{{ formErrors.password }}</span>
                        <input type="text"
                               name="password"
                               v-model="formData.password"
                               class="input-text"
                               id="password">
                    </div>

                    <div class="my-4"
                         :class="{'border-b border-red-400': formErrors.password_confirmation}">
                        <label class="form-label"
                               for="password_confirmation">Confirm Password</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.password_confirmation">{{ formErrors.password_confirmation }}</span>
                        <input type="text"
                               name="password_confirmation"
                               v-model="formData.password_confirmation"
                               class="input-text"
                               id="password_confirmation">
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button class="btn mr-4"
                                type="button"
                                @click="$emit('close')">Cancel
                        </button>
                        <button class="btn btn-navy"
                                type="submit"
                                :disabled="waiting"
                                :class="{'opacity-50': waiting}">Add
                        </button>
                    </div>
                </div>
            </vue-form>
        </div>
    </modal>

</template>

<script type="text/babel">
    import {Form} from "@dymantic/vue-forms";
    import {suggestPassword} from "../../libs/suggest-password";

    export default {
        props: ['show-form'],
        data() {
            const suggested_password = suggestPassword();
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: suggested_password,
                    password_confirmation: suggested_password,
                })
            };
        },

        methods: {
            teacherAdded() {
                const suggested_password = suggestPassword();
                this.form.resetFields({
                    password: suggested_password,
                    password_confirmation: suggested_password,
                });
                this.$emit('added-teacher-user');
                this.$emit('close');
            },

            failedToSubmit() {
                this.$emit('close');
                this.$emit('failed-add-teacher');
            }
        }
    }
</script>

<style scoped
       lang="less"
       type="text/less">

</style>
