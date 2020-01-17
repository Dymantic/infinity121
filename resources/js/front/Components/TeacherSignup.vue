<template>
    <div>
        <form action="" @submit.prevent="submit" class="max-w-md mx-auto">
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
            <div class="my-4" :class="{'border-b border-red-400': formErrors.phone}">
                <label class="form-label" for="phone">{{ labels.phone }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.phone">{{ formErrors.phone }}</span>
                <input type="text" name="phone" v-model="formData.phone" class="input-text" id="phone">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.age}">
                <label class="form-label" for="age">{{ labels.age }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.age">{{ formErrors.age }}</span>
                <input type="text" name="age" v-model="formData.age" class="input-text" id="age">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.years_in_taiwan}">
                <label class="form-label" for="years_in_taiwan">{{ labels.years_in_taiwan}}</label>
                <span class="text-xs text-red-400" v-show="formErrors.years_in_taiwan">{{ formErrors.years_in_taiwan }}</span>
                <input type="text" name="years_in_taiwan" v-model="formData.years_in_taiwan" class="input-text" id="years_in_taiwan">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.available_hours_per_week}">
                <label class="form-label" for="available_hours_per_week">{{ labels.available_hours_per_week }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.available_hours_per_week">{{ formErrors.available_hours_per_week }}</span>
                <input type="text" name="available_hours_per_week" v-model="formData.available_hours_per_week" class="input-text" id="available_hours_per_week">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.teaching_experience}">
                <label class="form-label" for="teaching_experience">{{ labels.teaching_experience }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.teaching_experience">{{ formErrors.teaching_experience }}</span>
                <textarea v-model="formData.teaching_experience" class="input-text input-textbox h-32" id="teaching_experience"></textarea>
            </div>
            <div class="py-12 text-center">
                <button type="submit" :disabled="waiting" class="btn btn-dark">Sign Up &gt;</button>
            </div>
        </form>
        <modal :show="showSuccessModal" @close="showSuccessModal = false">
            <div class="max-w-sm p-8">
                Boom baby!
            </div>
        </modal>
        <modal :show="showFailureModal" @close="showFailureModal = false">
            <div class="max-w-sm p-8">
                Oh kak!
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">

    function baseFormDataValues() {
        return {
            name: '',
            email: '',
            phone: '',
            age: '',
            years_in_taiwan: '',
            available_hours_per_week: '',
            teaching_experience: '',
        };
    }

    function blankFormErrors() {
        return Object.keys(baseFormDataValues())
                     .reduce((acc, key) => {
                         acc[key] = '';
                         return acc;
                     }, {});
    }

    export default {
        props: ['labels'],

        data() {
            return {
                waiting: false,
                showSuccessModal: false,
                showFailureModal: false,
                formData: baseFormDataValues(),
                formErrors: blankFormErrors(),
            };
        },

        methods: {
            submit() {
                this.formErrors = blankFormErrors();
                this.waiting = true;
                axios.post("/teachers/sign-up", this.formData)
                .then(this.onSubmission)
                .catch(({response}) => this.onFailure(response))
                .then(() => this.waiting = false);
            },

            onSubmission() {
                this.formData = baseFormDataValues();
                this.showSuccessModal = true;
            },

            onFailure({status, data}) {
                if(status === 422) {
                    Object.keys(data.errors)
                          .forEach(key => {
                              if(this.formErrors.hasOwnProperty(key)) {
                                  this.formErrors[key] = data.errors[key][0];
                              }
                          });
                    return;
                }
                this.showFailureModal = true;
            }
        }
    }
</script>

