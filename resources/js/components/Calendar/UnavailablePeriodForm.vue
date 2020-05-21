<template>
    <div>
        <div class="flex justify-between">
            <div class="w-80">
                <p class="text-lg font-bold">From:</p>
                <datepicker inline v-model="formData.from_date"></datepicker>
                <div class="my-6">
                    <label for="from_time"
                        >Time of day (24hr HH:mm format):</label
                    >
                    <input
                        type="text"
                        id="from_time"
                        v-model="formData.from_time"
                        class="input-text focus:outline-none"
                        placeholder="08:30"
                        :class="{ 'border-2 border-red-500': !fromTimeValid }"
                    />
                </div>
            </div>

            <div class="w-80">
                <p class="text-lg font-bold">To:</p>
                <datepicker inline v-model="formData.to_date"></datepicker>
                <div class="my-6">
                    <label for="to_time"
                        >Time of day (24hr HH:mm format):</label
                    >
                    <input
                        type="text"
                        id="to_time"
                        v-model="formData.to_time"
                        class="input-text focus:outline-none"
                        placeholder="08:30"
                        :class="{ 'border-2 border-red-500': !toTimeValid }"
                    />
                </div>
            </div>
        </div>
        <div class="my-6 flex justify-end">
            <button
                :disabled="!canSubmit || waiting"
                class="btn btn-navy"
                @click="submit"
            >
                Submit
            </button>
        </div>
    </div>
</template>

<script type="text/babel">
import Datepicker from "vuejs-datepicker";
import { dateAsYMDString, timeStringIsValid } from "../../libs/time_functions";
import { notify } from "../Messaging/notify";

export default {
    props: ["period"],

    components: {
        Datepicker
    },

    data() {
        return {
            waiting: false,
            formData: {
                from_date: new Date(),
                to_date: new Date(),
                from_time: "08:30",
                to_time: "22:00"
            }
        };
    },

    computed: {
        fromTimeValid() {
            return timeStringIsValid(this.formData.from_time);
        },

        toTimeValid() {
            return timeStringIsValid(this.formData.to_time);
        },

        canSubmit() {
            return this.fromTimeValid && this.toTimeValid;
        }
    },

    mounted() {
        if (this.period) {
            this.setForm();
        }
    },

    methods: {
        setForm() {
            this.formData = {
                from_date: new Date(this.period.starts_date),
                to_date: new Date(this.period.ends_date),
                from_time: this.period.starts_time,
                to_time: this.period.ends_time
            };
        },

        submit() {
            if (this.canSubmit) {
                this.$store
                    .dispatch(
                        "me/storeUnavailablePeriod",
                        this.parsedFormData()
                    )
                    .then(() => {
                        notify.success({
                            message: "Information has been updated."
                        });
                        this.$router.push("/me/unavailable-periods");
                    })
                    .catch(this.onError);
            }
        },

        parsedFormData() {
            const from = `${dateAsYMDString(this.formData.from_date)} ${
                this.formData.from_time
            }`;
            const to = `${dateAsYMDString(this.formData.to_date)} ${
                this.formData.to_time
            }`;

            return {
                formData: { from, to },
                id: this.period ? this.period.id : null
            };
        },

        onError({ status, data }) {
            if (status === 422) {
                return notify.warn({
                    message: "Your input is not valid, please double check."
                });
            }
            notify.error({
                message: "Something went wrong there, cannot save period."
            });
        }
    }
};
</script>
