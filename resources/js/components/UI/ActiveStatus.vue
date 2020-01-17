<template>
    <div>
        <span class="inline-block w-2 h-2 rounded-full mr-2 bg-red-500"
              :class="{'bg-red-500': !active, 'bg-green-500': active}"/>
        <span>{{ active ? 'Active' : 'Inactive'}}</span>
    </div>
</template>

<script type="text/babel">
    export default { props: ['active']}
</script>
