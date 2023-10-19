<template>
    <div class="p-5" dir="rtl">
        <div class="bg-gradient-to-r from-green-200 to-green-500 text-white flex items-center justify-between shadow-head p-8 rounded-[10px]">
            <div class="flex items-center text-white">
                <icon-calendar class="ml-4" color="#ffffff"/>
                <span>02/05/2023 - 09/05/2023</span>
            </div>
            <div class="text-3xl font-semibold uppercase ">{{ storeName }}</div>
            <pdf-logo />
        </div>
        <div class="p-5">
            <div class="bg-white p-4 rounded-[10px] flex justify-center">
                <div class="bg-green-100 p-5 rounded-[10px]">
                    <div class="flex items-center">
                        <icon-people class="text-green-400"/>
                        <span class="text-3xl mr-2.5">היום:</span>
                        <span class="text-3xl mr-2.5 text-green-400 font-medium">456</span>
                        <span class="text-red-500 font-medium mr-6">32%</span>
                        <icon-arrow-down class="text-red-500"/>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-[10px] flex justify-center">
                <icon-arrow-up />
                <icon-bags />
                <icon-book />
                <icon-sale />
                <icon-warning />
            </div>
        </div>
        <apexchart width="500" type="bar" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
import {
    PdfLogo,
    IconCalendar,
    IconPeople,
    IconArrowUp,
    IconArrowDown,
    IconSale,
    IconBook,
    IconBags,
    IconWarning
} from '@/_vendor/eyez/index'

export default {
    name: "Home",
    components: {
        IconPeople,
        PdfLogo,
        IconCalendar,
        IconArrowUp,
        IconArrowDown,
        IconSale,
        IconBook,
        IconBags,
        IconWarning
    },
    props: {
        storeName: {
            type: String
        }
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    id: 'vuechart-example'
                },
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
                }
            },
            series: [{
                name: 'series-1',
                data: [30, 40, 35, 50, 49, 60, 70, 91]
            }]
        }
    },
}
</script>

<style scoped>

</style>
