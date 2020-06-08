<template>
    <page v-if="customer">
        <page-header :title="customer.name">
            <delete-customer
                class="mr-4"
                :customer="customer"
            ></delete-customer>
            <router-link class="btn mr-4" :to="`/customers/${customer.id}/edit`"
                >Edit</router-link
            >
            <router-link
                class="btn btn-navy"
                :to="`/customers/${customer.id}/courses/create`"
                >New Course</router-link
            >
        </page-header>
        <div>
            <p><strong>Email: </strong>{{ customer.email }}</p>
            <p><strong>Phone: </strong>{{ customer.phone }}</p>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import DeleteCustomer from "./DeleteCustomer";
import { notify } from "../Messaging/notify";
export default {
    components: {
        Page,
        PageHeader,
        DeleteCustomer
    },

    mounted() {
        this.$store.dispatch("customers/fetchCustomers").catch(notify.error);
    },

    computed: {
        customer() {
            return this.$store.getters["customers/customerById"](
                this.$route.params.id
            );
        }
    }
};
</script>
