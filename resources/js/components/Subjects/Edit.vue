<template>
    <div>
        <div v-if="fetched_subject">
            <section class="flex justify-between items-center py-8">
                <h1 class="flex-1 text-5xl font-bold">Edit: {{ fetched_subject.title['en'] }}</h1>
                <div class="flex justify-end items-center">
                    <router-link class="btn mr-4"
                                 :to="`/show/${fetched_subject.id}`">Cancel
                    </router-link>

                </div>
            </section>
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
                <form @submit.prevent="submit">
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

    export default {
        components: {
            TrixVue
        },

        data() {
            return {
                fetched_subject: null,
                show_translation: 'en',
                languages: [],
                formData: {
                    title: {
                        en: '',
                        zh: '',
                        jp: '',
                    },
                    description: {
                        en: '',
                        zh: '',
                        jp: '',
                    },
                    writeup: {
                        en: '',
                        zh: '',
                        jp: '',
                    }
                },
                formErrors: {
                    title: '',
                    description: '',
                    writeup: ''
                }
            };
        },

        computed: {},

        mounted() {
            this.setForm();
        },

        computed: {
            unused_languages() {
                return ['en', 'zh', 'jp'].filter(lang => !this.languages.includes(lang));
            }
        },

        methods: {
            setForm() {
                this.$store.dispatch('subjects/getSubject', this.$route.params.id)
                    .then(subject => {
                        this.formData = {...subject};
                        this.fetched_subject = subject;
                        this.setLanguages();
                    })
                    .catch(console.log);
            },

            setLanguages() {
                const all_langs = [
                    ...Object.keys(this.fetched_subject.title),
                    ...Object.keys(this.fetched_subject.description),
                    ...Object.keys(this.fetched_subject.writeup),
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
                    id: this.fetched_subject.id,
                    formData: this.formData
                })
                    .then(() => this.$router.push(`/show/${this.fetched_subject.id}`))
                    .catch(this.onSaveError)
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

