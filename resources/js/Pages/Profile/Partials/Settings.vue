<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Off Days</h2>

        <p class="mt-1 text-sm text-gray-600">
            Set your off days.
        </p>
    </header>

    <div class="flex items-center gap-4 mt-4">
        <label v-for="(day, index) in days" class="flex items-center">
            <Checkbox name="off_days" @update:checked="updateDays(index)" :value="index" :checked="workDays.includes(index)" />

            <span class="ms-2 text-sm text-gray-600">{{ day }}</span>
        </label>
    </div>

    <header class="mt-8">
        <h2 class="text-lg font-medium text-gray-900">Title for age groups.</h2>

        <p class="mt-1 text-sm text-gray-600">
            Change the titles for age groups in dount chart.
        </p>
    </header>

    <div class="max-w-lg mt-4">
        <div class="mb-2">
            <InputLabel for="earlyYouth" value="Early Youth" />

            <TextInput
                id="earlyYouth"
                type="text"
                class="mt-1 block w-full"
                v-model="ageGroups.earlyYouth"
                required
            />
        </div>
        <div class="mb-2">
            <InputLabel for="youth" value="Youth" />

            <TextInput
                id="youth"
                type="text"
                class="mt-1 block w-full"
                v-model="ageGroups.youth"
                required
            />
        </div>
        <div class="mb-2">
            <InputLabel for="middleAge" value="Middle Age" />

            <TextInput
                id="middleAge"
                type="text"
                class="mt-1 block w-full"
                v-model="ageGroups.middleAge"
                required
            />
        </div>
        <div class="mb-2">
            <InputLabel for="middleAge" value="Middle Old" />

            <TextInput
                id="middleOld"
                type="text"
                class="mt-1 block w-full"
                v-model="ageGroups.middleOld"
                required
            />
        </div>
        <div class="mb-2">
            <InputLabel for="elderly" value="Elderly" />

            <TextInput
                id="elderly"
                type="text"
                class="mt-1 block w-full"
                v-model="ageGroups.elderly"
                required
            />
        </div>

        <PrimaryButton class="mt-2" @click="saveWorkingDays" :disabled="processing">Save</PrimaryButton>

        <p v-if="response.message.length > 0" :class="`${response.errors === true ? 'text-red-500' : 'text-green-500'}`">{{ response.message }}</p>
    </div>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
export default {
    name: "WorkingDays",
    components: {
        PrimaryButton,
        Checkbox,
        InputLabel,
        TextInput
    },
    props: {
        user: [Object],
        settings: [Object, Array],
    },
    data() {
        return {
            processing: false,
            workDays: this.settings?.workdays ?? [],
            ageGroups: this.settings?.ageGroups ?? {
                earlyYouth: 'Early Youth',
                youth: 'Youth',
                middleAge: 'Middle Age',
                middleOld: 'Middle Old',
                elderly: 'Elderly',
            },
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

            axios.post(route('profile.settings.update'), {
                workdays: this.workDays,
                ageGroups: this.ageGroups,
                user: this.user
            })
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
