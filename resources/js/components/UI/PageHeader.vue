<template>
    <section class="flex justify-between items-center py-8">
        <h1 class="text-hms-navy flex-1 text-5xl font-bold">{{ title }}</h1>
        <div class="flex justify-end items-center">
            <slot></slot>
        </div>
    </section>
</template>

<script type="text/babel">
export default {
    props: ["title"]
};
</script>
