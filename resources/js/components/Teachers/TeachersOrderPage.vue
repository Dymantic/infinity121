<template>
    <div class="max-w-5xl mx-auto px-6 pb-20">
        <page-header title="Set Teachers Order">

        </page-header>
        <p class="mb-12 text-lg max-w-2xl">You may set the order in which the teachers will appear on the website. Just drag and drop into the order you require.</p>

        <div ref="teachersList" class="max-w-2xl">
            <div v-for="teacher in teachers"
                 :key="teacher.id"
                 :data-id="teacher.id"
                 class="my-4 bg-gray-100 rounded p-4 flex items-center"
            >
                <img :src="teacher.avatar_thumb" class="w-12 h-12 rounded-full mr-10">
                <p class="text-xl">{{ teacher.name }}</p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import Sortable from "sortablejs";
    import PageHeader from "../UI/PageHeader";
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
            teachers() {
              return this.$store.getters['teachers/ordered'];
            }
        },

        mounted() {
            this.sortable = Sortable.create(this.$refs.teachersList, {
                onUpdate: this.onListChange,
            })
        },

        methods: {
            onListChange(a, b, c) {
                this.$store.dispatch('teachers/setOrder', this.sortable.toArray())
                .catch(notify.error);
            }
        }
    }
</script>
