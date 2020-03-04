<template>
    <span>
        <modal :show="showSuccess" @close="$emit('close-success')">
            <div class="max-w-sm">
                <div class="bg-hms-navy px-6 py-2">
                    <p class="type-h2 text-mustard">{{ dialog.success.heading }}</p>
                </div>
                <div class="p-8">
                    <p class="type-b1 text-deep-navy">{{ dialog.success.message }}</p>
                </div>
                <div class="flex p-4 justify-end">
                    <button class="btn btn-dark" @click="$emit('close-success')">{{ dialog.success.button }}</button>
                </div>
            </div>
        </modal>
        <modal :show="showError" @close="$emit('close-error')">
            <div class="max-w-sm">
                <div class="bg-red-500 px-6 py-2">
                    <p class="type-h2 text-white">{{ dialog.error.heading }}</p>
                </div>
                <div class="p-8">
                    <p class="type-b1">{{ dialog.error.message }}</p>
                </div>
                <div class="flex p-4 justify-end">
                    <button class="btn btn-grey" @click="$emit('close-error')">{{ dialog.error.button }}</button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
    export default {

        props: ['dialog', 'showSuccess', 'showError']
    }
</script>

<style scoped lang="scss" type="text/scss">

</style>
