<template>
    <div>
        <div class="flex">
            <p class="font-bold mr-4">Bio:</p>
            <button v-for="lang in langs" :key="lang" @click="showLang(lang)"
                    class="bg-transparent text-sm font-light uppercase mx-4 focus:outline-none"
                    :class="{'border-b-2 border-indigo-500': lang === current_lang}">{{ lang }}
            </button>
        </div>
        <div class="mt-4">
            {{ content }}
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['bio'],

        data() {
            return {
                current_lang: 'en',
            };
        },

        computed: {
            langs() {
                return Object.keys(this.bio);
            },

            content() {
                return this.bio[this.current_lang];
            }
        },

        methods: {
            showLang(lang) {
                this.current_lang = lang;
            }
        }
    }
</script>
