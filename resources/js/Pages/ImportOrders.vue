<template>
    <Head title="ייבוא הזמנות" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">ייבוא הזמנות</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-1 md:grid-cols-2 gap-5">
                    <header class="w-full">
                        <h2 class="text-lg font-medium text-gray-900">נא לבחור קובץ הזמנות לייבוא</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            <a
                                href="/download-file/eyez_download_sample.csv"
                                target="_blank"
                                download
                                class="font-semibold underline">
                            כאן ניתן להוריד תבנית מתאימה להזמנות
                            </a>

                        </p>
                    </header>
                    <div v-if="storesOptions.length > 0">
                        <div class="mb-4">
                            <base-select :options="storesOptions"
                                         id="store"
                                         label="נא לבחור חנות"
                                         :currentValue="selectedStore"
                                         @changed="(data) => form.storeId = data.value"
                            />
                        </div>
                        <div class="mb-4">
                            <InputLabel for="fileInput" value="נא לבחור קובץ" />

                            <input
                                id="fileInput"
                                type="file"
                                class="mt-1 block w-full ring-1 ring-inset ring-gray-300 py-1 px-4 rounded-md"
                                @change="handleFileChange"
                            />

                            <InputError class="mt-2" :message="form.errors?.orgId" />
                        </div>
                        <div class="flex items-center gap-4">
                            <PrimaryButton @click="uploadOrders" :disabled="form.processing">שמירה</PrimaryButton>

                            <p v-if="response.message.length > 0" :class="`${response.errors === true ? 'text-red-500' : 'text-green-500'}`">{{ response.message }}</p>
                        </div>
                    </div>
                    <div v-else class="">
                        Yout didn't opretail account or you don't have any stores created.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { BaseSelect } from '@/_vendor/eyez/index'
export default {
    name: "ImportOrders",
    components: {
        AuthenticatedLayout,
        Head,
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        Dropdown,
        DropdownLink,
        BaseSelect
    },
    props: {
        stores: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            storesOptions: this.stores.length > 0 ? this.mapOptions() : [],
            selectedStore: this.stores.length > 0 ? this.mapOptions()[0] : [],
            form: {
                storeId: this.stores.length > 0 ? this.mapOptions()[0].value : [],
                file: '',
            },
            response: {
                'errors': false,
                'message': ''
            }
        }
    },
    methods: {
        uploadOrders() {
            this.response = {
                'errors': false,
                'message': ''
            }
            this.form.processing = true;
            let formData = new FormData();
            formData.append('file', this.form.file);
            formData.append('storeId', this.form.storeId);

            axios.post(route('orders.import'), formData)
            .then((response) => {
                this.form.processing = true;
                this.response = response.data;
            })
        },
        handleFileChange(event) {
            console.log(event.target.files[0]);
            this.form.file = event.target.files[0];
        },
        mapOptions() {
            return this.stores.map((store) => {
                return {
                    value: store.id,
                    label: store.name
                }
            })
        }
    }
}
</script>
