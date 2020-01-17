<template>
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">My Profile</h1>
            <div class="flex justify-end items-center">
                <router-link class="nav-link active" to="/me/show">View</router-link>
                <router-link class="nav-link" to="/me/edit">Edit</router-link>
            </div>
        </section>
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    import {notify} from "../Messaging/notify";

    export default {

        mounted() {
            this.$store.dispatch('me/fetchProfile')
                .catch(() => notify.error({message: 'Unable to fetch profile'}));
        }
    }
</script>

<style scoped lang="scss" type="text/scss">

</style>
