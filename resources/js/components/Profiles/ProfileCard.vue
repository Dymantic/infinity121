<template>
    <div class="p-8 bg-gray-100 rounded shadow-lg flex justify-between">
        <div>
            <img :src="profile.avatar_thumb" alt="" class="w-24 h-24 rounded-full">
        </div>
        <div class="flex-1 pl-8">
            <p>
                <span class="font-bold text-lg">{{ profile.name }}</span>
                <span class="text-sm text-gray-600 ml-6">{{ profile.nationality.en }}</span>
            </p>
            <p class="text-gray-600">{{ profile.qualifications }}</p>
            <div class="my-4">
                <p><strong>Years experience: </strong>{{ profile.years_experience }}</p>
                <p><strong>Chinese ability: </strong>{{ profile.chinese_ability_full }}</p>
                <p>
                    <strong>Spoken Languages: </strong>
                    <span class="capitalize">{{ profile.spoken_languages.join(", ") }}</span></p>
            </div>
            <bio-viewer class="mt-6" :bio="profile.bio"></bio-viewer>
        </div>
    </div>
</template>

<script type="text/babel">
    import BioViewer from "./BioViewer";

    export default {
        components: {
            BioViewer,
        },

        props: ['profile'],
    }
</script>
