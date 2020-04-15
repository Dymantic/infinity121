<template>
    <div class="max-w-5xl mx-auto px-6">
        <page-header title="Subjects Order">
            <router-link to="/subjects" class="btn">Back to Subjects</router-link>
        </page-header>

        <p class="mb-12">Drag the subjects into the order you would like them to appear on the site.</p>

        <div ref="sortList">
            <div v-for="subject in subjects"
                 :key="subject.id"
                 :data-id="subject.id"
                 class="mb-8 max-w-2xl bg-white shadow-lg flex items-center">
                <div class="w-16 h-12 mr-6 grad-bg-indigo">
                    <img :src="subject.title_image.thumb" class="w-full h-full object-cover">
                </div>
                <p class="font-bold">{{ subject.title.en }}</p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import PageHeader from "../UI/PageHeader";
    import Sortable from "sortablejs";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            PageHeader,
        },

        data() {
            return {
                sortable: null,
            };
        },

        computed: {
            subjects() {
                return this.$store.getters['subjects/sorted_subjects'];
            }
        },

        mounted() {
            this.sortable = Sortable.create(this.$refs.sortList, {onUpdate: this.onSorted});
        },

        methods: {
            onSorted() {
                this.$store.dispatch("subjects/orderSubjects", this.sortable.toArray())
                .catch(notify);
            }
        }
    }
</script>
