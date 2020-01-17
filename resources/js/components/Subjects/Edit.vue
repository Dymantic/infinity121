<template>
    <div>
        <div v-if="subject">

            <div class="my-8 flex justify-between">
                <div>
                    <button v-for="lang in languages"
                            @click="show_translation = lang"
                            :key="lang"
                            type="button"
                            class="uppercase font-bold text-xl hover:text-indigo-500 mx-3"
                            :class="{'text-black border-b-2 border-indigo-500': show_translation == lang, 'text-gray-600': show_translation != lang}"
                    >{{ lang }}
                    </button>
                </div>

                <div class="flex items-center">
                    <p class="text-gray-600 text-lg">Add translation: </p>
                    <button v-for="lang in unused_languages"
                            :key="lang"
                            type="button"
                            class="text-xl font-bold text-indigo-500 mx-4 uppercase"
                            @click="addTranslation(lang)"
                    >&plus; {{ lang }}
                    </button>
                </div>
            </div>
            <div class="max-w-4xl mx-auto p-4 shadow-lg bg-white">
                <form v-if="form_ready" @submit.prevent="submit">
                    <div class="my-4 max-w-md"
                         :class="{'border-b border-red-400': formErrors.title}">
                        <label class="form-label"
                               for="title">Title</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.title">{{ formErrors.title }}</span>
                        <input type="text"
                               name="title.en"
                               v-model="formData.title[show_translation]"
                               class="input-text"
                               id="title">
                    </div>
                    <div class="my-4 max-w-md"
                         :class="{'border-b border-red-400': formErrors.description}">
                        <label class="form-label"
                               for="description">Description</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.description">{{ formErrors.description }}</span>
                        <textarea name="description"
                                  id="description"
                                  v-model="formData.description[show_translation]"
                                  class="input-text h-32"
                        ></textarea>

                    </div>
                    <div class="my-4"
                         :class="{'border-b border-red-400': formErrors.writeup}">
                        <label class="form-label"
                               for="writeup">Writeup</label>
                        <span class="text-xs text-red-400"
                              v-show="formErrors.writeup">{{ formErrors.writeup }}</span>
                        <div v-for="lang in languages"
                             :key="lang"
                             v-if="lang == show_translation">
                            <trix-vue v-model="formData.writeup[lang]"></trix-vue>
                        </div>


                    </div>
                    <div class="mt-12 flex justify-end">
                        <button type="submit"
                                class="btn btn-indigo">Save Changes
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import TrixVue from "@dymantic/vue-trix-editor";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            TrixVue
        },

        data() {
            return {
                show_translation: 'en',
                languages: [],
                form_ready: false,
                formData: {},
                formErrors: {
                    title: '',
                    description: '',
                    writeup: ''
                }
            };
        },

        computed: {
            subject_id() {
                return parseInt(this.$route.params.id);
            },

            subject() {
                return this.$store.getters['subjects/byId'](this.subject_id);
            },

            unused_languages() {
                return ['en', 'zh', 'jp'].filter(lang => !this.languages.includes(lang));
            }
        },

        mounted() {
            if (!this.subject) {
                return this.$store.dispatch('subjects/fetchSubjects')
                           .then(() => this.setForm())
                           .catch(notify.error);
            }

            this.setForm();
        },


        methods: {
            setForm() {
                if (this.subject) {
                    this.formData = {...this.subject};
                    this.setLanguages();
                    this.form_ready = true;
                }
            },

            setLanguages() {
                const all_langs = [
                    ...Object.keys(this.subject.title),
                    ...Object.keys(this.subject.description),
                    ...Object.keys(this.subject.writeup),
                ];

                this.languages = [...new Set(all_langs)];
            },

            addTranslation(lang) {
                this.formData.title[lang] = "";
                this.formData.description[lang] = "";
                this.formData.writeup[lang] = "";
                this.languages.push(lang);
                this.show_translation = lang;
            },

            submit() {
                this.$store.dispatch('subjects/saveSubject', {
                    id: this.subject.id,
                    formData: this.formData
                })
                    .then(() => this.onSaved())
                    .catch(this.onSaveError)
            },

            onSaved() {
                notify.success({message: 'This subject has been updated'});
                this.$router.push(`/subjects/${this.subject.id}/show`);
            },

            onSaveError({status, data}) {
                if (status === 422) {
                    Object.keys(data.errors).forEach(key => {
                        this.formErrors[key] = data.errors[key][0];
                    })
                }
            }
        }
    }
</script>

