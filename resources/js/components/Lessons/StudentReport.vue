<template>
    <div>
        <div class="flex justify-between">
            <div class="my-4 w-64">
                <span class="form-label">Interaction:</span>

                <div>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_interaction"
                            value="poor"
                            @change="$emit('input', report)"
                        />
                        <span>Poor</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_interaction"
                            value="okay"
                            @change="$emit('input', report)"
                        />
                        <span>Okay</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_interaction"
                            value="good"
                            @change="$emit('input', report)"
                        />
                        <span>Good</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_interaction"
                            value="excellent"
                            @change="$emit('input', report)"
                        />
                        <span>Excellent</span>
                    </label>
                </div>
            </div>
            <div class="my-4 w-64">
                <span class="form-label">Comprehension:</span>

                <div>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_comprehension"
                            value="poor"
                            @change="$emit('input', report)"
                        />
                        <span>Poor</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_comprehension"
                            value="okay"
                            @change="$emit('input', report)"
                        />
                        <span>Okay</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_comprehension"
                            value="good"
                            @change="$emit('input', report)"
                        />
                        <span>Good</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_comprehension"
                            value="excellent"
                            @change="$emit('input', report)"
                        />
                        <span>Excellent</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="flex justify-between">
            <div class="my-4 w-64">
                <span class="form-label">Confidence:</span>

                <div>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_confidence"
                            value="poor"
                            @change="$emit('input', report)"
                        />
                        <span>Poor</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_confidence"
                            value="okay"
                            @change="$emit('input', report)"
                        />
                        <span>Okay</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_confidence"
                            value="good"
                            @change="$emit('input', report)"
                        />
                        <span>Good</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_confidence"
                            value="excellent"
                            @change="$emit('input', report)"
                        />
                        <span>Excellent</span>
                    </label>
                </div>
            </div>
            <div class="my-4 w-64">
                <span class="form-label">Output:</span>

                <div>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_output"
                            value="poor"
                            @change="$emit('input', report)"
                        />
                        <span>Poor</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_output"
                            value="okay"
                            @change="$emit('input', report)"
                        />
                        <span>Okay</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_output"
                            value="good"
                            @change="$emit('input', report)"
                        />
                        <span>Good</span>
                    </label>
                    <label class="block">
                        <input
                            class="ml-4"
                            type="radio"
                            v-model="formData.st_output"
                            value="excellent"
                            @change="$emit('input', report)"
                        />
                        <span>Excellent</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["value"],
    data() {
        return {
            formData: {
                st_interaction: "",
                st_comprehension: "",
                st_confidence: "",
                st_output: ""
            }
        };
    },

    computed: {
        report() {
            return {
                interaction: this.formData.st_interaction,
                comprehension: this.formData.st_comprehension,
                confidence: this.formData.st_confidence,
                output: this.formData.st_output
            };
        }
    }
};
</script>

<style scoped lang="scss" type="text/scss"></style>
