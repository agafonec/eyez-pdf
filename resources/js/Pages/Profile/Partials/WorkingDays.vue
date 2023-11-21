<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Working days</h2>

        <p class="mt-1 text-sm text-gray-600">
            Set your working days.
        </p>
    </header>

    <div class="flex items-center gap-4">
        <label v-for="(day, index) in days" class="flex items-center">
            <Checkbox name="remember" @update:checked="updateDays(index)" :value="index" :checked="workDays.includes(index)" />

            <span class="ms-2 text-sm text-gray-600">{{ day }}</span>
        </label>
        <PrimaryButton @click="saveWorkingDays" :disabled="processing">Save</PrimaryButton>

        <p v-if="response.message.length > 0" :class="`${response.errors === true ? 'text-red-500' : 'text-green-500'}`">{{ response.message }}</p>
    </div>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
export default {
    name: "WorkingDays",
    components: {
        PrimaryButton,
        Checkbox
    },
    props: {
        workdays: [Object, Array],
    },
    data() {
        return {
            processing: false,
            workDays: this.workdays ?? [],
            response: {
                'errors': false,
                'message': ''
            }
        }
    },
    methods: {
        saveWorkingDays() {
            this.processing = true
            this.response.message = '';

            axios.post(route('profile.workdays.update'), {workdays: this.workDays})
            .then(response => {
                this.processing = false

                this.response = response.data;
            })
        },
        updateDays(day) {
            if (!this.workDays.includes(day)) {
                this.workDays.push(day);
            } else {
                this.workDays.splice(this.workDays.indexOf(day), 1);
            }
        }
    },
    setup() {
        const days =  ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
        return {
            days
        }
    }
}
</script>
