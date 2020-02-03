<template>
    <span>
        <button class="btn btn-red" @click="showDeleteModal = true">Delete</button>
        <modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-8 max-w-lg">
                <p class="text-lg font-bold text-red-500 mb-6">Delete this affiliate?</p>
                <p>Deleting this affiliate will completely remove it from the system, and can't be reversed. You also have the option of just retracting this affiliate if you don't mean to permanently delete it.</p>
                <div class="flex justify-end mt-6">
                    <button @click="showDeleteModal = false" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</button>
                    <button :disabled="waiting" @click="deleteAffiliate" class="btn btn-red">Yes, delete it!</button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
    export default {
        props: ['affiliate-id'],

        data() {
            return {
                waiting: false,
                showDeleteModal: false,
            };
        },

        methods: {
            deleteAffiliate() {
                this.waiting = true;
                this.$store.dispatch('affiliates/deleteAffiliate', this.affiliateId)
                .then(message => this.$emit('affiliate-deleted', message))
                .catch(message => this.$emit('cannot-delete', message))
                .then(() => {
                    this.waiting = false;
                    this.showDeleteModal = false;
                });
            }
        }
    }
</script>
