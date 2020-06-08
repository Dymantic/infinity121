<template>
    <div>
        <div class="flex items-center border px-3 rounded-lg mb-6">
            <search-icon class="h-5 text-gray-600"></search-icon>
            <input
                type="text"
                placeholder="filter by teacher name"
                v-model="query"
                class="flex-1 focus:outline-none p-2"
            />
        </div>
        <div class="h-40 overflow-auto">
            <div
                v-for="teacher in matching"
                :key="teacher.id"
                class="flex justify-between pb-1 my-2 border-b"
            >
                <div class="flex">
                    <img
                        :src="teacher.avatar_thumb"
                        class="h-5 w-5 mr-6 rounded-full"
                    />
                    <p>{{ teacher.name }}</p>
                </div>
                <button
                    @click="$emit('selected', teacher.id)"
                    class="text-xs font-bold text-gray-600 hover:text-hms-navy"
                >
                    Select
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import SearchIcon from "../UI/SearchIcon";
export default {
    components: {
        SearchIcon
    },

    data() {
        return {
            query: ""
        };
    },

    computed: {
        teachers() {
            return this.$store.state.teachers.teachers;
        },

        matching() {
            if (this.query.length < 3) {
                return this.teachers;
            }
            return this.teachers.filter(t =>
                t.name.toLowerCase().includes(this.query.toLowerCase())
            );
        }
    }
};
</script>
