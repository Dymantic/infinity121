<template>
    <div>
        <edit-form
            v-if="ready"
            :profile="me"
            redirect-on-success="/me/show"
        ></edit-form>
    </div>
</template>

<script type="text/babel">
import Edit from "./Edit";

export default {
    components: {
        "edit-form": Edit
    },

    computed: {
        me() {
            return this.$store.state.me.profile;
        },

        ready() {
            return this.me && this.me.id;
        }
    },

    mounted() {
        this.$store.dispatch("me/fetchProfile");
    }
};
</script>
