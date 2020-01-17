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
            <div class="my-4" :class="{'border-b border-red-400': formErrors.english_ability}">
                <label class="form-label" for="english_ability">{{ labels.english_ability }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.english_ability">{{ formErrors.english_ability }}</span>
                <select v-model="formData.english_ability" id="english_ability" class="input-text">
                    <option value="none">None</option>
                    <option value="little">Very little</option>
                    <option value="some">Some</option>
                    <option value="strong">Strong</option>
                </select>
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.subject_id}">
                <label class="form-label" for="subject_id">{{ labels.course }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.subject_id">{{ formErrors.subject_id }}</span>
                <select v-model="formData.subject_id" id="subject_id" class="input-text">
                    <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.title }}</option>
                </select>
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.address}">
                <label class="form-label" for="address">{{ labels.address }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.address">{{ formErrors.address }}</span>
                <input type="text" name="address" v-model="formData.address" class="input-text" id="address">
            </div>
            <div class="my-4" :class="{'border-b border-red-400': formErrors.message}">
                <label class="form-label" for="message">{{ labels.message }}</label>
                <span class="text-xs text-red-400" v-show="formErrors.message">{{ formErrors.message }}</span>
                <textarea v-model="formData.message" id="message" class="input-text input-textbox h-32"></textarea>
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
    function baseFormValues() {
        return {
            name: '',
            email: '',
            phone: '',
            age: '',
            english_ability: 'none',
            subject_id: 1,
            address: '',
            message: '',
        };
    }

    function blankFormErrors() {
        return Object.keys(baseFormValues())
        .reduce((acc, key) => {
            acc[key] = '';
            return acc;
        }, {});
    }

    export default {
        props: ['subjects', 'labels', 'course'],

        data() {
            return {
                waiting: false,
                showSuccessModal: false,
                showFailureModal: false,
                formData: baseFormValues(),
                formErrors: blankFormErrors(),
            };
        },

        mounted() {
            this.formData.subject_id = this.course;
        },

        methods: {
            submit() {
                this.formErrors = blankFormErrors();
                this.waiting = true;
                axios.post('/students/sign-up', this.formData)
                .then(this.onSubmission)
                .catch(({response}) => this.onFailure(response))
                .then(() => this.waiting = false);
            },

            onSubmission() {
                this.formData = baseFormValues();
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

<style>

</style>
