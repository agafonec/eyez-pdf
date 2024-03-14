<template>
    <Head title="נתוני זמן אמת" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6">
                <div class="p-4 md:p-8 bg-white shadow md:rounded-lg gap-5">
                    <header class="w-full mb-4">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Session logs for <span class="font-semibold">{{ user.name }}</span></h2>

                        <div v-if="logs.data.length > 0" class="relative overflow-x-auto sm:rounded-lg">
                            <table class="w-full text-sm text-right rtl:text-right text-gray-500 dark:text-gray-400 shadow-md">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Type
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Content
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <template v-for="logs in logs.data">
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ logs.type }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ logs.content }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ dateFormat(logs.created_at) }}
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                            <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 ml-1" aria-label="Table navigation">
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
                                    Showing <span class="font-semibold text-gray-900">{{ logs.from }}-{{ logs.to }}</span> of
                                    <span class="font-semibold text-gray-900">{{ logs.total }}</span>
                                </span>
                                <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                                    <li v-for="link in logs.links" class="group/pagination">
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
                        </div>
                        <div v-else>
                            User don't have any logs yet
                        </div>
                    </header>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import moment from 'moment';
export default {
    name: "Logs",
    components: {
        AuthenticatedLayout
    },
    props: {
        logs: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            required: true,
        }
    },
    methods: {
        dateFormat(date) {
            return moment(date).format('YYYY-MM-DD HH:mm:ss')
        }
    }
}
</script>
