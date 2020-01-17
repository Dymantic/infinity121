<template>
    <div>
        <h3 class="text-3xl mb-8">{{ title }}</h3>
        <div class="p-8 shadow bg-white my-12">
            <table class="w-full">
                <thead>
                <tr>
                    <th class="text-left font-normal uppercase text-gray-600 tracking-wide text-sm">Name</th>
                    <th class="text-left font-normal uppercase text-gray-600 tracking-wide text-sm">Roles</th>
                    <th class="text-left font-normal uppercase text-gray-600 tracking-wide text-sm">Email</th>
                </tr>
                </thead>
                <tbody class="">
                <tr v-for="user in users" :key="user.id">
                    <td class="py-2">
                        <router-link :to="`/users/${user.id}`" class="text-black hover:text-indigo-500">{{ user.name }}</router-link>
                    </td>
                    <td class="py-2">
                        <span v-if="user.is_admin" class="mr-4 uppercase text-sm tracking-wide">Admin</span>
                        <span v-if="user.is_teacher" class="mr-4 uppercase text-sm tracking-wide">Teacher</span>
                    </td>
                    <td class="py-2">
                        <a target="_blank" :href="`mailto:${user.email}`" class="no-underline text-gray-500 hover:text-indigo-500">{{ user.email }}</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['users', 'title']
    }
</script>
