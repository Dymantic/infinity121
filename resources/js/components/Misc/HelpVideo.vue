<template>
    <div class="mb-16">
        <div class="max-w-md">
            <p class="text-xl font-bold text-hms-navy">{{ title }}</p>
            <p class="my-4">{{ description }}</p>
        </div>
        <div class="w-full">
            <div class="w-full relative" style="padding-bottom: 56.25%">
                <iframe
                    class="inset-0 absolute w-full h-full"
                    :src="`https://www.youtube.com/embed/${video}`"
                    frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["title", "description", "video"]
};
</script>
