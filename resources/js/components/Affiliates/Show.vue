<template>
    <div class="max-w-4xl mx-auto" v-if="affiliate">
        <page-header :title="affiliate.name.en">
            <delete-affiliate class="mr-4"
                              @affiliate-deleted="deleted"
                              @cannot-delete="deleteError"
                              :affiliate-id="affiliate.id"
            />
            <router-link class="btn btn-navy" :to="`/affiliates/${affiliate.id}/edit`">Edit</router-link>
        </page-header>
        <div class="my-12 p-4 shadow">
            <p class="uppercase tracking-wide text-gray-600 font-bold">Link</p>
            <p class="hover:text-hms-navy"><a target="_blank" :href="affiliate.link">{{ affiliate.link }}</a></p>
        </div>
        <div class="flex justify-between p-4 shadow">
            <div>
                <p class="font-bold uppercase tracking-wide">
                    <span v-show="affiliate.is_public" class="text-green-500">Public</span>
                    <span v-show="!affiliate.is_public" class="text-red-500">Private</span>
                </p>
                <p class="my-6 max-w-xs">
                    <span v-if="affiliate.is_public">This affiliate will appear on the public website. If you would like to remove it from the site, but not completely delete it, you may retract it.</span>
                    <span v-else>This affiliate will not appear on the site. When you are ready to let it be pubic, you may publish it.</span>
                </p>
                <button :disabled="waiting" class="btn btn-navy" @click="togglePublish">
                    <span v-if="affiliate.is_public">Retract</span>
                    <span v-else>Publish</span>
                </button>
            </div>
            <div>
                <image-upload :upload-url="`/admin/api/affiliates/${affiliate.id}/image`"
                              :initial-src="affiliate.logo_thumb"
                              :preview-width="256"
                              :aspect-x="3"
                              :aspect-y="2"
                              :max-size="15"
                              @invalid-file-selected="invalidFileError"
                              @image-upload-failed="uploadError"
                              @image-uploaded="uploadedImage"
                              class="w-64 mx-auto"
                />
                <p class="max-w-xs text-gray-600 text-sm">Click above to upload a logo or image for this affiliate. The image should ideally be more than 600px wide, and will be cropped to a 3:2 ratio.</p>
            </div>
        </div>
        <div v-for="lang in languages" :key="lang.code" class="my-8 p-4 shadow">
            <p class="text-gray-600 uppercase tracking-wide font-bold mb-6">{{ lang.full }}</p>
            <p class="text-2xl">{{ affiliate.name[lang.code] }}</p>
            <p class="max-w-md">{{ affiliate.description[lang.code] }}</p>
        </div>
    </div>
</template>

<script type="text/babel">
    import PageHeader from "../UI/PageHeader";
    import DeleteAffiliate from "./DeleteAffiliate";
    import {ImageUpload} from "@dymantic/imagineer";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            PageHeader,
            ImageUpload,
            DeleteAffiliate,
        },

        data() {
            return {
                waiting: false,
                affiliate_id: null,
                languages: [
                    {code: 'en', full: 'English'},
                    {code: 'zh', full: 'Chinese'},
                    {code: 'jp', full: 'Japanese'},
                ]
            };
        },

        computed: {
            affiliate() {
                return this.$store.getters['affiliates/byId'](parseInt(this.affiliate_id));
            }
        },

        watch: {
            '$route': {
                handler: function (to) {
                    this.affiliate_id = to.params.id;
                },
                immediate: true,
            }
        },

        methods: {

            togglePublish() {
                this.waiting = true;
                this.affiliate.is_public ? this.retract() : this.publish();
            },

            publish() {
                this.$store.dispatch('affiliates/publishAffiliate', this.affiliate.id)
                    .then(notify.success)
                    .catch(notify.error)
                    .then(() => this.waiting = false);
            },

            retract() {
                this.$store.dispatch('affiliates/retractAffiliate', this.affiliate.id)
                    .then(notify.success)
                    .catch(notify.error)
                    .then(() => this.waiting = false);
            },

            invalidFileError(message) {
                notify.warn({message});
            },

            uploadError(message) {
                notify.error({message});
            },

            uploadedImage() {
                notify.success({message: "Image has been saved."});
            },

            deleted(message) {
                notify.success(message);
                this.$router.push("/affiliates");
            },

            deleteError(message) {
                notify.error(message);
            }


        }
    }
</script>
