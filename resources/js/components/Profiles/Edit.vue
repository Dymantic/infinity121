<template>
    <div>
        <form action=""
              @submit.prevent="submitForm">
            <div class="p-8 bg-white shadow">
                <p class="font-bold text-lg mb-4">Basic Info</p>
                <div class="flex justify-between">
                    <div class="my-4 mr-4 w-1/2"
                         :class="{'border-b border-red-400': formErrors.name}">
                        <label class="form-label"
                               for="name">Name</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.name">{{ formErrors.name }}</span>
                        <input type="text"
                               name="name"
                               v-model="formData.name"
                               class="input-text"
                               id="name">
                    </div>
                    <div class="my-4 ml-4 w-1/2"
                         :class="{'border-b border-red-400': formErrors.nationality}">
                        <label class="form-label"
                               for="nationality">Nationality</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.nationality">{{ formErrors.nationality }}</span>
                        <input type="text"
                               name="nationality"
                               v-model="formData.nationality"
                               class="input-text"
                               id="nationality">
                    </div>
                </div>


            </div>

            <div class="p-8 bg-white my-12 shadow">
                <p class="font-bold text-lg mb-4">About you</p>
                <p class="text-gray-600 my-4">Describe yourself in around 180 characters. Keep it brief and interesting.</p>
                <div class="">
                    <div class="three-grid">
                        <div v-for="(text, lang) in formData.bio"
                             class=""
                             :class="{'border-b border-red-400': formErrors.bio}">
                            <label class="form-label"
                                   :for="`bio_${lang}`">{{ lang }}</label>
                            <span class="text-xs text-red-400"
                                  v-show="formErrors.bio">{{ formErrors.bio }}</span>
                            <textarea name="bio"
                                      v-model="formData.bio[lang]"
                                      class="input-text h-24"
                                      :id="`bio_${lang}`"></textarea>
                        </div>
                    </div>

                    <div v-if="missing_translations.length" class="mt-4">
                        <p class="form-label mb-2">add translation:</p>
                        <button class="sm-btn btn-indigo uppercase mr-4"
                                v-for="language in missing_translations"
                                type="button"
                                @click="addBioTranslation(language)">{{ language }}
                        </button>
                    </div>
                </div>
            </div>


            <div class="p-8 bg-white mt-12 shadow">
                <p class="font-bold text-lg mb-4">Teaching and Qualifications</p>
                <div class="my-4"
                     :class="{'border-b border-red-400': formErrors.teaching_since}">
                    <label class="form-label mb-4"
                           for="teaching_since">When did you start teaching?</label>
                    <span class="text-xs text-red-400"
                          v-show="formErrors.teaching_since">{{ formErrors.teaching_since }}</span>
                    <input type="text"
                           class="input-text" v-model="formData.teaching_since" id="teaching_since" placeholder="e.g. 2012">
                </div>
                <div class="my-8"
                     :class="{'border-b border-red-400': formErrors.chinese_ability}">
                    <p class="form-label mb-4">Chinese ability</p>
                    <span class="text-xs text-red-400"
                          v-show="formErrors.chinese_ability">{{ formErrors.chinese_ability }}</span>
                    <div v-for="level in chinese_abilities">
                        <input type="radio"
                               :value="level.value"
                               v-model="formData.chinese_ability"
                               :id="level.key">
                        <label :for="level.key"
                               class="pl-4">{{ level.description }}</label>
                    </div>
                </div>
                <div class="my-4 max-w-sm"
                     :class="{'border-b border-red-400': formErrors.qualifications}">
                    <label class="form-label"
                           for="qualifications">Qualifications</label>
                    <span class="text-xs text-red-400"
                          v-show="formErrors.qualifications">{{ formErrors.qualifications }}</span>
                    <input type="text"
                           name="qualifications"
                           v-model="formData.qualifications"
                           class="input-text"
                           id="qualifications">
                </div>

            </div>
            <div class="flex justify-end my-12">
                <button type="submit" class="btn-indigo btn">Save changes</button>
            </div>



        </form>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {
        data() {
            return {
                bio_languages: ['en', 'zh', 'jp'],

                formData: {
                    id: '',
                    name: '',
                    bio: '',
                    nationality: '',
                    teaching_since: 2019,
                    chinese_ability: 1,
                    teaching_specialities: '',
                    qualifications: ''
                },
                formErrors: {
                    name: '',
                    bio: '',
                    nationality: '',
                    teaching_since: '',
                    chinese_ability: '',
                    teaching_specialities: '',
                    qualifications: ''
                }
            };
        },

        computed: {
            missing_translations() {
                const current = Object.keys(this.formData.bio);
                return this.bio_languages.filter(lang => !current.includes(lang));
            },

            chinese_abilities() {
                return this.$store.state.profiles.chinese_abilities;
            }
        },

        mounted() {
            this.$store.dispatch('profiles/fetchProfile')
                .then(() => this.resetFormData())
                .catch(console.log);
            this.resetFormData();
        },

        methods: {
            resetFormData() {
                this.formData = {...this.$store.state.profiles.profile}
            },

            submitForm() {
                this.$store.dispatch('profiles/saveProfile', this.formData)
                    .then(() => this.$router.push('/show'))
                    .catch(({status, data}) => {
                        if(status === 422) {
                            this.setFormErrors(data.errors);
                            return notify.warn({message: 'Some of your input is not valid'});
                        }
                        notify.error({message: 'There was a problem saving your changes.'});
                    });
            },

            setFormErrors(errors) {
                Object.keys(errors).forEach(key => this.formErrors[key] = errors[key][0]);
            },

            addBioTranslation(lang) {
                this.$set(this.formData.bio, lang, '');
            }
        }
    }
</script>

