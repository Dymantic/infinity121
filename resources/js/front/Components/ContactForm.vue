<template>
    <div>
        <form @submit.prevent="submit" class="max-w-md mx-auto">
            <div class="my-4" :class="{'border-b border-red-400': formErrors.name}">
                <label class="form-label" for="name">{{ labels.name }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.name">{{ formErrors.name }}</span>
                <input type="text" name="name" v-model="formData.name" class="input-text" id="name">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.email}">
                <label class="form-label" for="email">{{ labels.email }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.email">{{ formErrors.email }}</span>
                <input type="email" name="email" v-model="formData.email" class="input-text" id="email">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.message}">
                <label class="form-label" for="message">{{ labels.message }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.message">{{ formErrors.message }}</span>
                <textarea name="message" v-model="formData.message" class="p-2 input-text input-textbox h-48" id="message"/>
            </div>
            <div class="py-12 text-center">
                <button type="submit" :disabled="waiting" class="btn btn-dark">{{ labels.send }} &gt;</button>
            </div>
        </form>
        <modal :show="showSuccessModal" @close="showSuccessModal = false">
            <div class="max-w-sm">
                <div class="bg-hms-navy px-6 py-2">
                    <p class="type-h2 text-mustard">{{ dialog.success.heading }}</p>
                </div>
                <div class="p-8">
                    <p class="type-b1 text-deep-navy">{{ dialog.success.message }}</p>
                </div>
                <div class="flex p-4 justify-end">
                    <button class="btn btn-dark" @click="showSuccessModal = false">{{ dialog.success.button }}</button>
                </div>
            </div>
        </modal>
        <modal :show="showErrorModal" @close="showErrorModal = false">
            <div class="max-w-sm">
                <div class="bg-red-500 px-6 py-2">
                    <p class="type-h2 text-white">{{ dialog.error.heading }}</p>
                </div>
                <div class="p-8">
                    <p class="type-b1">{{ dialog.error.message }}</p>
                </div>
                <div class="flex p-4 justify-end">
                    <button class="btn btn-grey" @click="showErrorModal = false">{{ dialog.error.button }}</button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['labels', 'dialog'],

        data() {
            return {
                showSuccessModal: false,
                showErrorModal: false,
                waiting: false,
                formData: {
                    name: '',
                    email: '',
                    message: '',
                },
                formErrors: {
                    name: '',
                    email: '',
                    message: '',
                }
            };
        },

        methods: {
            submit() {
                this.clearFormErrors();
                this.waiting = true;
                axios.post("/contact", this.formData)
                .then(this.handleSuccess)
                .catch(({response}) => this.handleError(response))
                .then(() => this.waiting = false);
            },

            handleSuccess() {
                this.formData = {
                    name: '',
                    email: '',
                    message: '',
                };
                this.showSuccessModal = true;
            },

            handleError({status, data}) {
                if(status === 422) {
                    return this.setValidationMessages(data.errors);
                }

                this.showErrorModal = true;
            },

            setValidationMessages(errors) {
              Object.keys(this.formErrors)
              .forEach(key => {
                  if(errors.hasOwnProperty(key)) {
                      this.formErrors[key] = errors[key][0];
                  }
              });
            },

            clearFormErrors() {
                this.formErrors = {
                    name: '',
                    email: '',
                    message: '',
                };
            }
        }
    }
</script>
