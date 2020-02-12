<template>
    <div>
        <form @submit.prevent="submit">
            <div class="w-full flex">
                <div class="flex-1">
                    <p class="font-bold text-xl">English</p>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.en_name}">
                        <label class="form-label" for="en_name">Name</label>
                        <span class="text-xs text-red-400" v-show="formErrors.en_name">{{ formErrors.en_name }}</span>
                        <input type="text" name="en_name" v-model="formData.en_name" class="input-text" id="en_name">
                    </div>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.en_description}">
                        <label class="form-label" for="en_description">Description</label>
                        <span class="text-xs text-red-400" v-show="formErrors.en_description">{{ formErrors.en_description }}</span>
                        <textarea id="en_description" v-model="formData.en_description"
                                  class="h-48 input-text input-textbox"/>
                    </div>
                </div>

                <div class="px-6 flex-1">
                    <p class="font-bold text-xl">Chinese</p>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.zh_name}">
                        <label class="form-label" for="zh_name">Name</label>
                        <span class="text-xs text-red-400" v-show="formErrors.zh_name">{{ formErrors.zh_name }}</span>
                        <input type="text" name="zh_name" v-model="formData.zh_name" class="input-text" id="zh_name">
                    </div>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.zh_description}">
                        <label class="form-label" for="zh_description">Description</label>
                        <span class="text-xs text-red-400" v-show="formErrors.zh_description">{{ formErrors.zh_description }}</span>
                        <textarea id="zh_description" v-model="formData.zh_description"
                                  class="h-48 input-text input-textbox"/>
                    </div>
                </div>

                <div class="flex-1">
                    <p class="font-bold text-xl">Japanese</p>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.jp_name}">
                        <label class="form-label" for="jp_name">Name</label>
                        <span class="text-xs text-red-400" v-show="formErrors.jp_name">{{ formErrors.jp_name }}</span>
                        <input type="text" name="jp_name" v-model="formData.jp_name" class="input-text" id="jp_name">
                    </div>
                    <div class="my-4" :class="{'border-b border-red-400': formErrors.jp_description}">
                        <label class="form-label" for="jp_description">Description</label>
                        <span class="text-xs text-red-400" v-show="formErrors.jp_description">{{ formErrors.jp_description }}</span>
                        <textarea id="jp_description" v-model="formData.jp_description"
                                  class="h-48 input-text input-textbox"/>
                    </div>
                </div>
            </div>

            <div class="my-4 max-w-sm" :class="{'border-b border-red-400': formErrors.link}">
                <label class="form-label" for="link">Link to website</label>
                <span class="text-xs text-red-400" v-show="formErrors.link">{{ formErrors.link }}</span>
                <input type="text" name="link" v-model="formData.link" class="input-text" id="link" placeholder="https://example.com">
            </div>

            <div class="my-6">
                <button :disabled="waiting" type="submit" class="btn btn-navy">Submit</button>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {

        props: ['affiliate'],

        data() {
            return {
                waiting: false,
                formData: {
                    en_name: '',
                    en_description: '',
                    zh_name: '',
                    zh_description: '',
                    jp_name: '',
                    jp_description: '',
                    link: '',
                },
                formErrors: {
                    en_name: '',
                    en_description: '',
                    zh_name: '',
                    zh_description: '',
                    jp_name: '',
                    jp_description: '',
                    link: '',
                }
            };
        },

        mounted() {
            if(this.affiliate) {
                this.formData.en_name = this.affiliate.name.en;
                this.formData.zh_name = this.affiliate.name.zh;
                this.formData.jp_name = this.affiliate.name.jp;
                this.formData.en_description = this.affiliate.description.en;
                this.formData.zh_description = this.affiliate.description.zh;
                this.formData.jp_description = this.affiliate.description.jp;
                this.formData.link = this.affiliate.link;
            }
        },

        methods: {
            submit() {
                this.waiting = true;
                this.clearFormErrors();
                this.affiliate ? this.updateAffiliate() : this.createAffiliate();
            },

            createAffiliate() {
                this.$store.dispatch('affiliates/addAffiliate', this.consolidatedData())
                    .then(affiliate => this.$emit('affiliate-created', affiliate))
                    .catch(({status, data}) => this.handleError(status, data))
                    .then(() => {
                        this.waiting = false;
                    });
            },

            updateAffiliate() {
                this.$store.dispatch('affiliates/updateAffiliate', {
                    id: this.affiliate.id,
                    data: this.consolidatedData(),
                })
                    .then(affiliate => this.$emit('affiliate-updated', affiliate))
                    .catch(({status, data}) => this.handleError(status, data))
                    .then(() => {
                        this.waiting = false;
                    });
            },

            handleError(status, data) {
                if(status === 422) {
                    if(data.errors.hasOwnProperty('link')) {
                        this.formErrors.link = data.errors.link[0];
                    }

                    if(data.errors.hasOwnProperty('name')) {
                        this.formErrors.en_name = 'At least one name must be supplied';
                        this.formErrors.zh_name = 'At least one name must be supplied';
                        this.formErrors.jp_name = 'At least one name must be supplied';
                    }

                    return;
                }

                notify.error({message: 'Sorry, currently unable to save data.'});
            },

            consolidatedData() {
                return {
                    name: {
                        en: this.formData.en_name,
                        zh: this.formData.zh_name,
                        jp: this.formData.jp_name,
                    },
                    description: {
                        en: this.formData.en_description,
                        zh: this.formData.zh_description,
                        jp: this.formData.jp_description,
                    },
                    link: this.formData.link,
                }
            },

            clearFormErrors() {
                this.formErrors = {
                    en_name: '',
                    en_description: '',
                    zh_name: '',
                    zh_description: '',
                    jp_name: '',
                    jp_description: '',
                    link: '',
                }
            }
        }
    }
</script>
