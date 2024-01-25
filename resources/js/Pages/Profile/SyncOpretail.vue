<template>
    <Head title="Sync with opretail"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sync with opretail</h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Start sync process with cameras.</h2>
                    </header>
                    <p v-if="messages?.length > 0" :class="`${errors === true ? 'text-red-500' : 'text-green-500'}`">{{ messages }}</p>

                    <progress-bar v-if="progress && batchId"
                                  :completed="progress.percent"
                                  :success="progress.success"
                                  :failed="progress.error"
                                  label="Syncing store" />

                    <div v-else class="max-w-md">
                        <div v-if="storesOptions.length > 0">
                            <div class="mb-4">
                                <base-select :options="storesOptions"
                                             id="store"
                                             label="נא לבחור חנות"
                                             :currentValue="selectedStore"
                                             @changed="(data) => selectedStore = data"
                                />
                            </div>
                            <div class="">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Select starting date you want to sync from</label>
                                <date-picker style="direction: ltr" mode="date"
                                             :max-date="new Date()"
                                             v-model="selectedDate"
                                             :popover="{
                                          visibility: 'hover-focus',
                                          placement: 'bottom',
                                          isInteractive: true,
                                        }">
                                    <template #default="{ togglePopover, inputValue, inputEvents }">
                                        <div class="flex justify-center overflow-hidden w-full" >
                                            <button
                                                class="flex items-center justify-center text-gray-900 ring-1 ring-inset ring-gray-300 rounded-md w-full py-1.5"
                                                @click="() => togglePopover()">
                                                <span>{{ formattedDate() }}</span>
                                                <icon-calendar class="mr-4"/>
                                            </button>
                                            <input
                                                :value="inputValue"
                                                v-on="inputEvents"
                                                class="flex-grow p-0 bg-white dark:bg-gray-700 opacity-0 w-0"
                                            />
                                        </div>
                                    </template>
                                </date-picker>
                            </div>
                            <PrimaryButton class="mt-4" @click="startSync">Start Sync</PrimaryButton>
                        </div>
                        <div v-else>
                            You have no stores created. Contact main user or support.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {
    ProgressBar,
    BaseSelect,
    IconCalendar
} from '@/_vendor/eyez/index'
import { DatePicker } from 'v-calendar'
import moment from "moment";
export default {
    name: "SyncOpretail",
    components: {
        AuthenticatedLayout,
        Head,
        PrimaryButton,
        ProgressBar,
        BaseSelect,
        DatePicker,
        IconCalendar
    },
    props: {
        stores: {
            type: [Object, Array]
        },
        errors: [Object, Boolean],
        messages: [Array, String]
    },
    data() {
        return {
            storesOptions: this.stores?.length > 0 || typeof this.stores === 'object' ? this.mapOptions() : [],
            selectedStore: this.stores?.length > 0 || typeof this.stores === 'object' ? this.mapOptions()[0] : [],
            selectedDate: new Date(),
            syncing: false,
            fetchInterval: null,
            batchId: null,
            progress: null,
        }
    },
    methods: {
        formattedDate() {
            return moment(this.selectedDate).format('YYYY-MM-DD')
        },
        startSync() {
            this.syncing = true;
            axios.post(route('profile.sync.start', {
                store: this.selectedStore.value,
            }), {
                startDate: this.selectedDate,
            })
            .then(response => {
                this.batchId = response.data.batchId
                this.fetchInterval = setInterval(this.fetchProgress, 300);
            })
            .catch(error => {
                console.log(error)
            })
        },
        mapOptions() {
            if (typeof this.stores === 'object' ) {
                return Object.values(this.stores).map((store) => {
                    return {
                        value: store.id,
                        label: store.name
                    }
                })
            } else {
                return this.stores.map((store) => {
                    return {
                        value: store.id,
                        label: store.name
                    }
                })
            }
        },
        fetchProgress() {
            axios.get(route('jobs.progress', {
                batchId: this.batchId
            }))
                .then(response => {
                    this.progress = {
                        percent: response.data.progress?.processedJobs,
                        success: response.data.progress?.processedJobs,
                        error: response.data.progress.failedJobs,
                        total: response.data.progress?.totalJobs,
                        totalCompleted: response.data.progress?.processedJobs + response.data.progress.failedJobs,
                    }

                    this.progress.percent = Math.round((this.progress.totalCompleted / this.progress.total) * 100)

                    if (this.progress.totalCompleted === this.progress.total) {
                        clearInterval(this.fetchInterval);
                    }
                })
                .catch(error => {
                    console.error('Error fetching progress:', error);
                });
        },
    }
}
</script>
