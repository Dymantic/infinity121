<template>
    <div v-if="country">
        <div
            class="flex justify-between items-center border-b border-gray-200 pb-2"
        >
            <p class="text-xl font-bold">{{ country.name }}</p>
            <div class="flex items-center">
                <set-region :country="country">
                    <plus-icon
                        class="h-6 text-gray-600 hover:text-green-500"
                    ></plus-icon>
                </set-region>
                <set-country class="mx-4" :country="country">
                    <edit-icon
                        class="h-4 text-gray-600 hover:text-blue-500"
                    ></edit-icon>
                </set-country>
                <delete-location
                    type="country"
                    :item-id="country.id"
                    :name="country.name"
                ></delete-location>
            </div>
        </div>
        <div
            v-for="region in country.regions"
            :key="region.id"
            class="my-8 pl-4"
        >
            <div
                class="flex justify-between items-center pb-2 border-b border-gray-200 mb-4"
            >
                <p class="text-lg font-bold">{{ region.name }}</p>
                <div class="flex items-center">
                    <set-area :region="region">
                        <plus-icon
                            class="h-6 text-gray-600 hover:text-green-500"
                        ></plus-icon>
                    </set-area>
                    <set-region
                        :country="country"
                        :region="region"
                        class="mx-4"
                    >
                        <edit-icon class="h-4"></edit-icon>
                    </set-region>
                    <delete-location
                        type="region"
                        :name="region.name"
                        :item-id="region.id"
                    ></delete-location>
                </div>
            </div>
            <div>
                <div
                    v-for="area in region.areas"
                    :key="area.id"
                    class="flex mb-2"
                >
                    <p class="mr-6">{{ area.area_name }}</p>
                    <set-area :region="region" :area="area" class="mr-3">
                        <edit-icon class="h-3"></edit-icon>
                    </set-area>
                    <delete-location
                        :name="area.name"
                        :item-id="area.id"
                        type="area"
                        size="small"
                    ></delete-location>
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
import DeleteLocation from "./DeleteLocation";
import PlusIcon from "../UI/PlusIcon";
export default {
    components: {
        SetCountry,
        SetRegion,
        SetArea,
        EditIcon,
        DeleteLocation,
        PlusIcon
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
