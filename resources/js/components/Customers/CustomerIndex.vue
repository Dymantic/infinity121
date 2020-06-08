<template>
    <div class="max-w-4xl mx-auto px-6">
        <page-header title="Infinity Customers">
            <add-customer></add-customer>
        </page-header>
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="text-left pl-2 py-2">Name</th>
                        <th class="text-left pl-2 py-2">Email</th>
                        <th class="text-left pl-2 py-2">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(customer, index) in customers"
                        :key="customer.id"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td class="pl-2 py-2">
                            <router-link
                                :to="`/customers/${customer.id}`"
                                class="hover:underline"
                            >
                                {{ customer.name }}
                            </router-link>
                        </td>
                        <td class="pl-2 py-2">{{ customer.email }}</td>
                        <td class="pl-2 py-2">{{ customer.phone }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../UI/PageHeader";
import AddCustomer from "./AddCustomer";
import { notify } from "../Messaging/notify";
export default {
    components: {
        PageHeader,
        AddCustomer
    },

    computed: {
        customers() {
            return this.$store.state.customers.customers;
        }
    },

    mounted() {
        this.$store.dispatch("customers/fetchCustomers").catch(notify.error);
    }
};
</script>
