<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-2 gap-5">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                    <UpdatePasswordForm class="max-w-xl"/>
                </div>

                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <settings :settings="opretail.settings"/>
                </div>

                <div v-if="roles.includes('admin')" class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <OpretailnformationForm  :opretail="opretail"/>
                </div>

                <div v-if="roles.includes('admin')" class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <ApiToken :api-token="eyezApiToken"/>
                </div>

                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import OpretailnformationForm from "./Partials/OpretailnformationForm.vue";
import Settings from "./Partials/Settings.vue";
import ApiToken from "./Partials/ApiToken.vue";

export default {
    name: "Edit",
    components: {
        AuthenticatedLayout,
        DeleteUserForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
        Head,
        OpretailnformationForm,
        Settings,
        ApiToken
    },
    props: {
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
        opretail: {
            type: Object
        },
        roles: {
            type: Array
        },
        eyezApiToken: {
            type: String
        },
        showSuperAdmin: {
            type: Boolean,
            default: false,
        }
    }
}
</script>
