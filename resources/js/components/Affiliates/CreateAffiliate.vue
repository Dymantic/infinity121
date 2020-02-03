<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="Add New Affiliate">
            <router-link to="/affiliates" class="btn btn-indigo">Back</router-link>
        </page-header>

        <affiliate-form @affiliate-created="onCreated"></affiliate-form>
    </div>
</template>

<script type="text/babel">
    import Form from "./Form";
    import PageHeader from "../UI/PageHeader";
    import {notify} from "../Messaging/notify";

    export default {
        components: {
            'affiliate-form': Form,
            PageHeader,
        },

        methods: {
            onCreated({id, name}) {
                notify.success({message: `${name.en} has been added.`});
                this.$router.push(`/affiliates/${id}`);
            }
        }
    }
</script>
