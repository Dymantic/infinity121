<template>
    <div>
        <div class="p-4 bg-white shadow flex justify-between">
            <div class="w-1/2">
                <p class="text-xl">{{ profile.name }}</p>
                <p class="text-gray-600">{{ profile.nationality.en }}</p>
                <p class="text-gray-600">{{ profile.qualifications }}</p>
                <div class="my-8">
                    <p class="form-label">Teaching since</p>
                    <p>{{ profile.teaching_since }}</p>
                </div>
                <div class="my-8">
                    <p class="form-label">Chinese ability</p>
                    <p class="capitalize">{{ profile.chinese_ability_full }}</p>
                </div>

                <div>
                    <div class="flex items-center">
                        <p class="form-label mr-4">Bio</p>
                        <button v-for="(text, lang) in profile.bio"
                                @click="bio_lang = lang"
                                class="uppercase tracking-wide border-b-2 border-white text-sm mx-4"
                                :class="{'border-indigo-500': bio_lang === lang}"
                        >{{ lang }}
                        </button>
                    </div>

                    <p class="my-4">
                        <span>{{ bio_text }}</span>
                    </p>
                </div>
            </div>
            <div class="w-1/2 text-center">
                <image-upload upload-url="/admin/api/me/profile/image"
                              :initial-src="avatar"
                              :preview-width="256"
                              :aspect-x="1"
                              :aspect-y="1"
                              :max-size="15"
                              @invalid-file-selected="invalidFileError"
                              @image-upload-failed="uploadError"
                              @image-uploaded="uploadedImage"
                              class="w-64 mx-auto"
                              v-if="profile_loaded"
                />
                <p class="w-64 text-sm text-gray-600 mt-4 mx-auto text-left">Click to upload your avatar. The image will be cropped to a square, and you should use an image bigger than 400 x 400px.</p>
            </div>

        </div>

    </div>

</template>

<script type="text/babel">
    import {ImageUpload} from "@dymantic/imagineer";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            ImageUpload,
        },

        data() {
            return {
                bio_lang: 'en'
            };
        },

        computed: {
            profile() {
                return this.$store.state.me.profile;
            },

            profile_loaded() {
                return this.profile.name;
            },

            avatar() {
                return this.profile.avatar_thumb;
            },

            bio_text() {
                return this.profile.bio[this.bio_lang];
            },
        },

        methods: {
            invalidFileError(message) {
                notify.warn({message});
            },

            uploadError() {
                notify.error({message: 'There was a problem uploading your image.'});
            },

            uploadedImage() {
                notify.success({message: 'Your image has been uploaded successfully.'})
            }
        }
    }
</script>

