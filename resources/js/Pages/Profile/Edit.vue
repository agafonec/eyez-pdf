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
                    <UpdatePasswordForm class="max-w-xl" :user="mustVerifyEmail"/>
                </div>
                <div v-if="roles.includes('admin') && currentUser !== undefined">
                    <OpretailnformationForm :opretail="opretail" :user="currentUser"/>
                </div>

                <div v-if="roles.includes('admin') && currentUser !== undefined && !opretail.errors" class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <settings :settings="currentUser.settings" :user="currentUser" :stores="stores"/>
                </div>

                <div v-if="roles.includes('admin') && !opretail.errors">
                    <ApiToken :api-token="eyezApiToken" :user="currentUser"/>
                </div>

                <div v-if="roles.includes('admin') || roles.includes('main_user')" class="p-4 md:p-8 bg-white shadow md:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Start sync process with cameras.</h2>
                    </header>
                    <PrimaryButton v-if="roles.includes('admin')" @click="$inertia.visit(route('admin.sync-store', {user: currentUser.id}))">Go to sync page</PrimaryButton>
                    <PrimaryButton v-else @click="$inertia.visit(route('profile.sync-opretail'))">Go to sync page</PrimaryButton>
                </div>

<!--                <div v-if="!roles.includes('admin')" class="p-4 md:p-8 bg-white shadow md:rounded-lg">-->
<!--                    <DeleteUserForm class="max-w-xl" :user="currentUser"/>-->
<!--                </div>-->
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
import PrimaryButton from "@/Components/PrimaryButton.vue"

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
        ApiToken,
        PrimaryButton
    },
    props: {
        mustVerifyEmail: {
            type: Boolean,
        },
        currentUser: {
            type: Object,
        },
        status: {
            type: String,
        },
        opretail: {
            type: Object
        },
        stores: {
            type: [Object, Array]
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
