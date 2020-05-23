<template>
    <div v-if="country">
        <div class="flex justify-between items-center">
            <p class="text-xl font-bold">{{ country.name }}</p>
            <set-country :country="country">
                <edit-icon class="h-4"></edit-icon>
            </set-country>
            <set-region :country="country"></set-region>
        </div>
        <div
            v-for="region in country.regions"
            :key="region.id"
            class="my-8 p-4"
        >
            <div class="flex justify-between items-center">
                <p class="text-lg font-bold">{{ region.name }}</p>
                <set-region :country="country" :region="region">
                    <edit-icon class="h-4"></edit-icon>
                </set-region>
                <set-area :region="region"></set-area>
            </div>
            <div>
                <div
                    v-for="area in region.areas"
                    :key="area.id"
                    class="flex justify-between"
                >
                    <p>{{ area.name }}</p>
                    <set-area :region="region" :area="area">
                        <edit-icon class="h-4"></edit-icon>
                    </set-area>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import SetCountry from "./SetCountry";
import SetRegion from "./SetRegion";
import SetArea from "./SetArea";
import EditIcon from "../UI/EditIcon";
export default {
    components: {
        SetCountry,
        SetRegion,
        SetArea,
        EditIcon
    },

    computed: {
        country() {
            return this.$store.getters["locations/countryById"](
                this.$route.params.id
            );
        }
    }
};
</script>
