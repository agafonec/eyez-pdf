<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Rename Stores</h2>
    </header>

    <div class="flex items-center gap-4 mt-4 mb-6">
        <template v-for="(store, index) in storesSettings">
            <div class="mb-2">
                <InputLabel :for="`store-${store.id}`" :value="stores.find(obj => obj.id === store.id).name" />

                <TextInput
                    :id="`store-${store.id}`"
                    type="text"
                    class="mt-1 block w-full"
                    @input="(e) => {this.formError = e.target.value < 3}"
                    v-model="store.name"
                    required
                />

                <p v-if="store.name.length < 3" class="text-red-500">Store name can't be less than 3 characters.</p>
            </div>
        </template>
    </div>

    <header>
        <h2 class="text-lg font-medium text-gray-900">Hide Stores for user</h2>
    </header>

    <div class="flex items-center gap-4 mt-4">
        <label v-for="(store, index) in stores" class="flex items-center">
            <Checkbox name="hidden_stores"
                      @update:checked="updateHiddenStores(store.dep_id)"
                      :value="store.dep_id" :checked="hiddenStores.includes(store.dep_id)" />

            <span class="ms-2 text-sm text-gray-600">{{ store.name }}</span>
        </label>
    </div>

    <div class="flex gap-x-10 flex-wrap">
        <header class="w-full mt-8">
            <h1 class="text-lg font-medium text-gray-900">Select your working days and time.</h1>
            <p class="mt-1 text-sm text-gray-600">
                Everything that will be disabled will not be counted in dashboard.
            </p>
        </header>
        <div v-for="(store, index) in storesSettings">
            <header class="mt-8">
                <h2 class="text-xl font-bold text-gray-900">{{ store.name }}</h2>
                <div class="text-md font-medium text-gray-900">Schedule</div>
            </header>

            <div class="gap-4 mt-4 w-auto">
                <div v-for="(day, index) in days" class="grid grid-cols-3 w-max gap-2 items-center mb-2">
                    <label class="flex items-center col-span-1">
                        <Checkbox name="off_days"
                                  @update:checked="updateDays(store, index)"
                                  :value="index"
                                  :checked="!store.settings.daysoff.includes(index)" />

                        <span class="ms-2 text-sm text-gray-600">{{ day }}</span>
                    </label>

                    <div class="grid grid-cols-2 gap-4 mb-4 col-span-2 relative" style="direction: ltr;">
                        <div v-if="store.settings.daysoff.includes(index)"
                             class="absolute flex items-center justify-center col-span-2 opacity-80 left-0 top-0 w-full h-full z-20 bg-white font-semibold text-red-700">DISABLED</div>
                        <div class="text-center">
                            <div class="font-semibold">From time</div>
                            <date-picker
                                v-model="store.settings.workdays.find((obj) => obj.dayOfWeek === index).timeStart"
                                mode="time"
                                is24hr />
                        </div>
                        <div class="text-center">
                            <div class="font-semibold">To time</div>
                            <date-picker
                                v-model="store.settings.workdays.find((obj) => obj.dayOfWeek === index).timeEnd"
                                mode="time"
                                is24hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <header class="mt-8">
        <h2 class="text-lg font-medium text-gray-900">Title for age groups.</h2>

        <p class="mt-1 text-sm text-gray-600">
            Change the titles for age groups in dount chart.
        </p>
    </header>

    <div class="max-w-lg mt-4">
        <label class="flex items-center mb-4">
            <Checkbox name="hide_age_description" @update:checked="disableChildFromConversion = !disableChildFromConversion" :checked="disableChildFromConversion" />

            <span class="ms-2 text-sm text-gray-600">Disable children from conversion rate calculation.</span>
        </label>

        <label class="flex items-center mb-4">
            <Checkbox name="hide_age_description" @update:checked="hideAgeDescription = !hideAgeDescription" :checked="hideAgeDescription" />

            <span class="ms-2 text-sm text-gray-600">Hide age description. (age range)</span>
        </label>
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
    </div>

    <PrimaryButton class="mt-2" @click="updateSettings" :disabled="processing || formError">Save</PrimaryButton>

    <p v-if="response.message.length > 0" :class="`${response.errors === true ? 'text-red-500' : 'text-green-500'}`">{{ response.message }}</p>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { DatePicker } from 'v-calendar'
import moment from "moment"

export default {
    name: "WorkingDays",
    components: {
        PrimaryButton,
        Checkbox,
        InputLabel,
        TextInput,
        DatePicker
    },
    props: {
        user: [Object],
        stores: [Object, Array],
        settings: [Object, Array],
    },
    data() {
        return {
            processing: false,
            hiddenStores: this.settings?.hiddenStores ?? [],
            workDays: this.settings?.workdays ?? [],
            hideAgeDescription: this.settings?.hideAgeDescription ?? false,
            disableChildFromConversion: this.settings?.disableChildFromConversion ?? false,
            storesSettings: this.getStoreSettings(this.stores),
            ageGroups: this.settings?.ageGroups ?? {
                earlyYouth: 'Early Youth',
                youth: 'Youth',
                middleAge: 'Middle Age',
                middleOld: 'Middle Old',
                elderly: 'Elderly',
            },
            formError: false,
            response: {
                'errors': false,
                'message': ''
            }
        }
    },
    methods: {
        getStoreSettings(stores) {
            return stores.map(store => {
                store.settings = store.settings ?? {
                    daysoff: null,
                    workdays: null
                }

                let defaultWorkdays = [];
                this.days.forEach((day, index) => {
                    defaultWorkdays.push({
                        dayOfWeek: index,
                        timeStart: (new Date()).setHours(0, 0, 0, 0),
                        timeEnd: (new Date()).setHours(23, 59, 59, 999)
                    })
                })

                store.settings.daysoff = store.settings?.daysoff ?? []
                store.settings.workdays = store.settings?.workdays ?? defaultWorkdays

                return store
            })
        },
        updateSettings() {
            this.processing = true
            this.response.message = '';

            axios.post(route('profile.settings.update'), {
                storesSettings: this.storesSettings,
                ageGroups: this.ageGroups,
                hideAgeDescription: this.hideAgeDescription,
                disableChildFromConversion: this.disableChildFromConversion,
                hiddenStores: this.hiddenStores,
                user: this.user
            })
            .then(response => {
                this.processing = false

                this.response = response.data;
            })
        },
        updateDays(store, day) {
            if (!store.settings.daysoff.includes(day)) {
                store.settings.daysoff.push(day);
            } else {
                store.settings.daysoff.splice(store.settings.daysoff.indexOf(day), 1);
            }
        },
        updateHiddenStores(store) {
            if (!this.hiddenStores.includes(store)) {
                this.hiddenStores.push(store);
            } else {
                this.hiddenStores.splice(this.hiddenStores.indexOf(store), 1);
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

<style>
.vc-time-picker .vc-time-header {
    display: none !important;
}
</style>
