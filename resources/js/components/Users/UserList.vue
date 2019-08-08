<template>
    <div>
        <h3 class="text-3xl mb-8">{{ title }}</h3>
        <div class="pl-8">
            <div v-for="user in users" class="max-w-xl p-2 bg-gray-100 mb-1 flex justify-between">
                <a :href="`/admin/pages/users/${user.id}`" class="text-black hover:text-indigo-500">{{ user.name }}</a>
                <a :href="`mailto:${user.email}`" class="no-underline text-gray-500 hover:text-indigo-500">{{ user.email }}</a>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['users', 'title']
    }
</script>
