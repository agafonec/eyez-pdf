<template>
    <Head title="Manage Users" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg gap-5">
                    <header class="w-full mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Manage Users</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            You can create, edit, or remove users here.
                        </p>
                    </header>

                    <div class="relative sm:rounded-lg">
                        <div class="flex justify-end">
                            <PrimaryButton @click="$inertia.visit(route('register'))" class="mb-4 ms-auto">Register User</PrimaryButton>
                        </div>

                        <table v-if="users.data.length > 0" class="w-full text-sm text-right rtl:text-right text-gray-500 dark:text-gray-400 shadow-md">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    User Id
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created At
                                </th>
                                <th scope="col" class="px-6 py-3 text-left">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="user in users.data">
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.id }}
                                    </th>
                                    <td class="px-6 py-4 dark:text-white font-semibold">
                                        {{ user.name }}
                                    </td>
                                    <td class="px-6 py-4 dark:text-white font-semibold">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4 dark:text-white font-semibold">
                                        {{ createdAt(user.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 text-left dark:text-white">
                                        <Dropdown align="center">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center bg-white rounded-md dark:bg-gray-500
                                                        font-semibold uppercase hover:text-gray-700 focus:outline-none transition p-0 rounded-full dark:text-white"
                                                    >
                                                        <svg fill="none" height="32" viewBox="0 0 24 24" width="32" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor"><path d="m12 8.29999c.718 0 1.3-.58202 1.3-1.29999s-.582-1.29999-1.3-1.29999-1.3.58202-1.3 1.29999.582 1.29999 1.3 1.29999z"/><path d="m12 13.3c.718 0 1.3-.582 1.3-1.3s-.582-1.3-1.3-1.3-1.3.582-1.3 1.3.582 1.3 1.3 1.3z"/><path d="m13.3 17c0 .718-.582 1.3-1.3 1.3s-1.3-.582-1.3-1.3.582-1.3 1.3-1.3 1.3.582 1.3 1.3z"/></g>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content class="max-w-[50px]">
                                                <DropdownLink align="center" href="#" @click="$inertia.visit(route('profile.dashboard.view', {user: user.id}))">Dashboard</DropdownLink>
                                                <DropdownLink align="center" :href="`/users/${user.id}/logs`">Logs</DropdownLink>
                                                <DropdownLink align="center" href="#" @click="$inertia.visit(route('register', {parent_user: user.id}))">Add User</DropdownLink>
                                                <DropdownLink align="center" :href="`/users/${user.id}`">Edit</DropdownLink>
                                                <DropdownLink align="center" href="#" @click="deleteUser(user.id)">Delete</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </td>
                                </tr>

                                <tr v-if="user.sub_users"
                                    v-for="subUser in user.sub_users"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ subUser.id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ subUser.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ subUser.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ createdAt(subUser.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 text-left">
                                        <Dropdown align="center">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center bg-white rounded-md dark:bg-gray-500
                                                        font-semibold uppercase hover:text-gray-700 focus:outline-none transition p-0 rounded-full dark:text-white"
                                                    >
                                                        <svg fill="none" height="32" viewBox="0 0 24 24" width="32" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor"><path d="m12 8.29999c.718 0 1.3-.58202 1.3-1.29999s-.582-1.29999-1.3-1.29999-1.3.58202-1.3 1.29999.582 1.29999 1.3 1.29999z"/><path d="m12 13.3c.718 0 1.3-.582 1.3-1.3s-.582-1.3-1.3-1.3-1.3.582-1.3 1.3.582 1.3 1.3 1.3z"/><path d="m13.3 17c0 .718-.582 1.3-1.3 1.3s-1.3-.582-1.3-1.3.582-1.3 1.3-1.3 1.3.582 1.3 1.3z"/></g>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <DropdownLink align="center" :href="`/users/${subUser.id}/logs`">Logs</DropdownLink>
                                                <DropdownLink align="center" :href="`/users/${subUser.id}`">Edit</DropdownLink>
                                                <DropdownLink align="center" href="#" @click="deleteUser(subUser.id)">Delete</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                        <nav v-if="users.data.length > 0" class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 ml-1" aria-label="Table navigation">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span class="font-semibold text-gray-900">{{ users.from }}-{{ users.to }}</span> of <span class="font-semibold text-gray-900">{{ users.total }}</span></span>
                            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                                <li v-for="link in users.links" class="group/pagination">
                                    <a :href="link.url"
                                       :class="[
                                           'flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500',
                                           'hover:text-gray-700',
                                           'group-first/pagination:rounded-s-lg group-last/pagination:rounded-e-lg',
                                           {'opacity-75 cursor-not-allowed' : link.url === null},
                                           `${ link.active ? 'dark:bg-gray-700 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : ' bg-white border border-gray-300 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700' }`,
                                           'dark:border-gray-700 dark:text-gray-400 dark:hover:text-white']" v-html="link.label"></a>
                                </li>
                            </ul>
                        </nav>

                        <div v-else>
                            You don't have any users. you can create one.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import moment from "moment";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
export default {
    name: "Users",
    components: {
        AuthenticatedLayout,
        Head,
        PrimaryButton,
        Dropdown,
        DropdownLink
    },
    props:  {
        users: {
            type: [Array, Object],
        }
    },
    methods: {
        createdAt(date) {
            return  moment(date).format('YYYY-MM-DD')
        },
        deleteUser(userId) {
            axios.post(route('admin.profile.destroy', {user: userId}))
                .then((response) => {
                    window.location.reload()
                })
        }
    }
}
</script>

<style scoped>

</style>
