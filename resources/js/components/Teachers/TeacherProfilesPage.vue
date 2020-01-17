<template>
    <div class="max-w-4xl mx-auto">
        <page-header title="iTC Teachers">

        </page-header>
        <div class="my-12">
            <table class="w-full max-w-xl">
                <thead>
                <tr>
                    <th class="text-left pl-4">Name</th>
                    <th class="text-left">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover:bg-gray-200" v-for="teacher in teachers" :key="teacher.id">
                    <td class="py-2 pl-4 hover:text-indigo-500"><router-link :to="`/teachers/${teacher.id}`">{{ teacher.name }}</router-link></td>
                    <td>
                        <active-status :active="teacher.is_public" />
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script type="text/babel">
    import PageHeader from "../UI/PageHeader";
    import ActiveStatus from "../UI/ActiveStatus";
    export default {

        components: {
            PageHeader,
            ActiveStatus,
        },

        computed: {
            teachers() {
                return this.$store.state.teachers.teachers;
            }
        }
    }
</script>
