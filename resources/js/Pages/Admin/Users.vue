<template>
    <Head title="Manage Users" />

    <AuthenticatedLayout>
        {{ users }}

        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg gap-5">
                    <header class="w-full mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Manage Users</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            You can create, edit, or remove users here.
                        </p>
                    </header>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="flex justify-end">
                            <PrimaryButton @click="$inertia.visit(route('register'))" class="mb-4 ms-auto">Register User</PrimaryButton>
                        </div>

                        <table v-if="users.data.length > 0" class="w-full text-sm text-right rtl:text-right text-gray-500 dark:text-gray-400">
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
                            <tr  v-for="user in users.data" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ user.id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ user.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ createdAt(user.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <a :href="`/users/${user.id}`" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    <a href="#" @click="deleteUser(user.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline me-4">Delete</a>
                                </td>
                            </tr>
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

export default {
    name: "Users",
    components: {
        AuthenticatedLayout,
        Head,
        PrimaryButton,
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
