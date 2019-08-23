<template>
    <div>
        <div v-if="fetched_subject">
            <section class="flex justify-between items-center py-8">
                <h1 class="flex-1 text-5xl font-bold">{{ fetched_subject.title['en'] }}</h1>
                <div class="flex justify-end items-center">
                    <router-link class="font-bold text-gray-600 hover:text-indigo-500 mr-4"
                                 to="/">All Subjects
                    </router-link>
                    <router-link class="btn btn-indigo"
                                 :to="`/edit/${subject_id}`">Edit
                    </router-link>
                </div>
            </section>
            <div class="my-8">
                <button v-for="lang in translations"
                        @click="show_translation = lang"
                        :key="lang"
                        type="button"
                        class="uppercase font-bold text-xl hover:text-indigo-500 mx-3"
                        :class="{'text-black border-b-2 border-indigo-500': show_translation == lang, 'text-gray-600': show_translation != lang}"
                >{{ lang }}
                </button>
            </div>
            <div class="max-w-4xl mx-auto shadow-lg bg-white mb-20">
                <div class="p-8 bg-blue-100 flex justify-between">
                    <div class="w-1/2 mr-4">
                        <p class="text-xl text-gray-800 font-bold">
                            {{ fetched_subject.title[show_translation] }}
                        </p>
                        <p class="mt-4 mb-12 text-gray-600">
                            {{ fetched_subject.description[show_translation] }}
                        </p>
                    </div>
                    <div class="w-1/2 text-center">
                        <image-upload :upload-url="image_upload_url"
                                      :initial-src="title_image"
                                      :preview-width="256"
                                      :aspect-x="4"
                                      :aspect-y="3"
                                      :max-size="15"
                                      @invalid-file-selected="invalidFileError"
                                      @image-upload-failed="uploadError"
                                      @image-uploaded="uploadedImage"
                                      class="w-64 mx-auto"
                                      v-if="fetched_subject"
                        ></image-upload>
                        <p class="w-64 text-sm text-gray-600 mt-4 mx-auto text-left">Click to upload an image for this
                                                                                     subject. The image will be cropped
                                                                                     to a 4:3 ratio, and you should use
                                                                                     an image bigger than 400 x
                                                                                     300px.</p>
                    </div>
                </div>

                <div class="p-8"
                     v-html="fetched_subject.writeup[show_translation]"></div>
            </div>
        </div>
        <div v-else
             class="text-5xl text-gray-500 mt-48 text-center">Subject not found
        </div>

    </div>
</template>

<script type="text/babel">
    import {ImageUpload} from "@dymantic/imagineer";

    export default {

        components: {
            ImageUpload,
        },

        data() {
            return {
                fetched_subject: null,
                show_translation: 'en',
            };
        },

        computed: {

            translations() {
                const all = [
                    ...Object.keys(this.fetched_subject.title),
                    ...Object.keys(this.fetched_subject.description),
                    ...Object.keys(this.fetched_subject.writeup),
                ];

                return [...new Set(all)];
            },

            subject_id() {
                return this.$route.params.id;
            },

            title_image() {
                if (!this.fetched_subject) {
                    return '';
                }

                return this.fetched_subject.title_image.thumb;
            },

            image_upload_url() {
                if (!this.fetched_subject) {
                    return '';
                }

                return `/admin/subjects/${this.fetched_subject.id}/image`;
            }
        },

        mounted() {
            this.$store.dispatch('subjects/getSubject', this.subject_id)
                .then(subject => this.fetched_subject = subject)
                .catch(console.log);
        },

        methods: {
            invalidFileError() {

            },

            uploadError() {

            },

            uploadedImage() {

            }
        }
    }
</script>

