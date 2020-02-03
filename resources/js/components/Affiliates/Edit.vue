<template>
    <div class="max-w-4xl mx-auto" v-if="ready">
        <page-header :title="affiliate.name.en">
            <router-link class="btn btn-indigo" :to="`/affiliates/${affiliate.id}`">Back</router-link>
        </page-header>
        <affiliate-form :affiliate="affiliate"
                        :key="affiliate.id"
                        @affiliate-updated="onSaved"
        />
    </div>
</template>

<script type="text/babel">
    import PageHeader from "../UI/PageHeader";
    import {notify} from "../Messaging/notify";
    import Form from "./Form";

    export default {
        components: {
            PageHeader,
            'affiliate-form': Form,
        },

        data() {
            return {
                ready: false,
                affiliate_id: null,
                affiliate: null,
            };
        },

        watch: {
            $route: {
                handler: function(to) {
                    this.ready = false;
                    this.affiliate_id = to.params.id;
                    this.$store.dispatch('affiliates/fetchById', this.affiliate_id)
                    .then(affiliate => this.setForm(affiliate))
                    .catch(notify.error);
                },
                immediate: true
            }
        },

        methods: {
            setForm(affiliate) {
                this.affiliate = affiliate;
                this.ready = true;
            },

            onSaved({name}) {
                notify.success({message: `${name.en || 'Affiliate'} has been updated.`});
            }
        }
    }
</script>
