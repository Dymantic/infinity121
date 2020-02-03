<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="Affiliates">
            <router-link to="/affiliates/create" class="btn btn-indigo">Add new</router-link>
        </page-header>
        <div>
            <div v-for="affiliate in affiliates" :key="affiliate.id" class="my-6 shadow">
                <router-link :to="`/affiliates/${affiliate.id}`">
                    <div class="flex">
                        <img :src="affiliate.logo_thumb" class="w-48" alt="">
                        <div class="p-4 flex-1">
                            <p class="text-lg mb-6">{{ affiliate.name.en }}</p>
                            <p class="text-gray-600 truncate w-80">{{ affiliate.description.en }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";
    import PageHeader from "../UI/PageHeader";

    export default {
        components: {
            PageHeader,
        },

        computed: {
            affiliates() {
                return this.$store.getters['affiliates/sorted'];
            }
        },

        mounted() {
            this.$store.dispatch('affiliates/fetchAffiliates')
            .catch(notify.error);
        }
    }
</script>
