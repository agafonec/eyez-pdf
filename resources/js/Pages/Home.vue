<template>
    <Head title="נתוני זמן אמת" />

    <AuthenticatedLayout>
        <div ref="componentToPrint" class="p-5 max-w-pdf-container mx-auto" dir="rtl">
            <div class="text-center grid md:grid-cols-4 grid-cols-2 justify-center mb-2 gap-4 max-w-3xl mx-auto">
                <PrimaryButton class="w-full block justify-center h-full"
                               @click="clearSummaryCache" >ריענון</PrimaryButton>
                <PrimaryButton class="w-full block justify-center h-full"
                               @click="printPage">Print PDF</PrimaryButton>

                <json-excel class="w-full block"
                            :fetch="fetchExportData"
                            :stringifyLongNum="true"
                            :fields="exportHeaders">

                    <PrimaryButton class="w-full justify-center h-full">ייצוא לאקסל</PrimaryButton>
                </json-excel>

                <json-excel class="w-full block"
                            :stringifyLongNum="true"
                            :data="exportAgeGender">
                    <PrimaryButton class="w-full justify-center h-full">ייצוא נתונים דמוגרפים</PrimaryButton>
                </json-excel>
            </div>
            <div class="relative bg-gradient-to-r from-green-200 to-green-500 text-white p-4 md:p-8 rounded-[10px] relative flex flex-col md:flex-row items-center justify-center md:justify-between">
                <pdf-logo  class="w-[100px] md:w-[225px] h-[36px] md:h-[81px] object-contain"/>
                <div class="text-3xl font-semibold uppercase ">
                    <Dropdown align="center">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center bg-transparent
                                    text-xl md:text-3xl font-semibold uppercase hover:text-gray-700 focus:outline-none transition"
                                >
<!--                                    <span>{{ currentStore.name }}</span>-->
                                    <span v-if="currentStore.id !== undefined">Eyez Store</span>
                                    <span v-else>All stores</span>
                                    <svg class="ms-2 -me-0.5 h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
<!--                            {{ store.name }}-->
                            <template v-if="roles.includes('admin')" v-for="(store, index) in availableStores">
                                <DropdownLink
                                    :href="route('profile.dashboard.view', {user: store.user_id, stores: store.dep_id})"
                                    v-if="!settings?.hiddenStores?.includes(store.dep_id)"
                                    align="center">Eyez Store {{ index }}</DropdownLink>
                            </template>
                            <template v-else v-for="(store, index) in availableStores">
                                <DropdownLink
                                    :href="route('home.show', {stores: store.dep_id})"
                                    v-if="!settings?.hiddenStores?.includes(store.dep_id)"
                                    align="center">Eyez Store {{ index }}</DropdownLink>
                            </template>

                            <DropdownLink v-if="showAllStoresLink && roles.includes('admin')"
                                          :href="route('profile.dashboard.view', {
                                              user: availableStores[0].user_id,
                                              stores: availableStores.map(obj => obj.dep_id).join(',')
                                          })"
                                          align="center">All stores</DropdownLink>
                            <DropdownLink v-else-if="showAllStoresLink" :href="route('home.show', {stores: availableStores.map(obj => obj.dep_id).join(',')})" align="center">All stores</DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <div class="hidden end-4 top-4 md:top-0 md:end-0 absolute md:relative md:block">
                    <date-picker style="direction: ltr" v-model.range="pickerRange"
                                 mode="date"
                                 :popover="false"
                                 :max-date="new Date()"
                                 @update:modelValue="onDateRangeChange">
                        <template #default="{ togglePopover, inputValue, inputEvents }">
                            <div class="flex justify-start overflow-hidden" >
                                <button
                                    class="flex items-center text-white"
                                    @click="() => togglePopover()">
                                    <span class="hidden md:block" v-html="dateRangeText()"></span>
                                    <icon-calendar class="mr-4" color="#ffffff"/>
                                </button>
                                <input
                                    :value="inputValue"
                                    v-on="inputEvents"
                                    class="flex-grow px-2 py-1 bg-white dark:bg-gray-700 opacity-0 w-0"
                                />
                            </div>
                        </template>
                    </date-picker>
                </div>
            </div>
            <div class="md:hidden p-4 bg-white mt-4 rounded-[10px]">
                <div class="bg-gray-100 rounded-md">
                    <date-picker style="direction: ltr"
                                 v-model.range="pickerRange" mode="date"
                                 :max-date="new Date()"
                                 :popover="{
                                  visibility: 'hover-focus',
                                  placement: 'bottom',
                                  isInteractive: true,
                                }"
                                 align="middle"
                                 @update:modelValue="onDateRangeChange">
                        <template #default="{ togglePopover, inputValue, inputEvents }">
                            <div class="flex justify-center overflow-hidden w-full" >
                                <button
                                    class="flex items-center justify-center text-gray-300 w-full py-1.5"
                                    @click="() => togglePopover()">
                                    <span v-html="dateRangeText()"></span>
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
            </div>

            <div class="py-5 md:p-5">
                <div class="mb-0 md:mb-5 bg-white p-4 rounded-t-[10px] sm:rounded-[10px] flex items-center justify-center flex-col md:flex-row gap-5 md:gap-10">
                    <div class="bg-green-100 w-full md:w-1/3 pt-10 pb-4 sm:py-10 rounded-[10px] relative text-center">
                        <div class="text-3xl mr-2.5 text-green-400 font-medium pt-8 mb-3 sm:mb-0 sm:pt-0">סה״כ מבקרים</div>

                        <div class="flex items-center justify-center flex-col sm:flex-row">
                            <div class="flex items-center">
                                <icon-people class="text-green-400"/>
                                <span class="text-3xl mr-2.5 text-green-400 font-medium">{{ storeData.walkInCount.toLocaleString() }}</span>
                            </div>
                            <div v-if="this.reportType === 'hours'" class="flex items-center max-md:mt-4">
                                <span :class="['font-medium md:ms-6 text-lg', `${storeData.walkInCount < avgWalkIn ? 'text-red-300' : 'text-green-300'}` ]">
                                    {{ percent(storeData.walkInCount, avgWalkIn) }}
                                </span>
                                <div v-if="storeData.walkInCount < avgWalkIn"
                                     class="flex items-center justify-center max-md:from-red-200 max-md:to-red-500 max-md:bg-gradient-to-t max-md:ms-3 rounded-full w-11 h-11 sm:w-auto sm:h-auto">
                                    <icon-arrow-down class="text-white sm:text-red-300 w-3 h-5 sm:w-2 sm:h-3.5"/>
                                </div>
                                <div v-else class="flex items-center justify-center max-md:from-green-200 max-md:to-green-500 max-md:bg-gradient-to-t max-md:ms-3 rounded-full w-11 h-11 sm:w-auto sm:h-auto">
                                    <icon-arrow-up class="text-white sm:text-green-300 w-3 h-5 sm:w-2 sm:h-3.5"/>
                                </div>
                            </div>
                            <span v-if="reportType === 'hours'"
                                  class="w-[90%] bg-green-300 text-white text-sm absolute rounded-md py-2 text-center top-4 left-4 md:py-0 md:top-2 md:left-2 md:bg-transparent md:text-green-300 md:w-auto">
                                ממוצע מבקרים: {{ avgWalkIn }}
                            </span>
                        </div>
                    </div>
                    <div v-if="summary" class="flex flex-wrap md:flex-nowrap items-center justify-between w-full md:w-2/3">
                        <stat-box variant="big" :stat="summary.week" icon-circle-class="bg-lime-200">
                            <template #icon>
                                <icon-people width="32" height="32" class="text-lime-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                        <stat-box variant="big" :stat="summary.month" icon-circle-class="bg-amber-200">
                            <template #icon>
                                <icon-people width="32" height="32" class="text-amber-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                        <stat-box variant="big"  :stat="summary.year" icon-circle-class="bg-green-50"  class="mx-auto md:mx-0 mt-4 md:mt-0">
                            <template #icon>
                                <icon-people width="32" height="32" class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 justify-center">
                    <div class="md:col-span-5">
                        <div class="grid grid-cols-2 gap-x-5 gap-y-3 relative bg-white p-4 rounded-b-[10px] md:gap-x-10 md:rounded-[10px]">
                            <label class="flex items-center col-span-2">
                                <Checkbox name="toggle_past_period"
                                          v-model:checked="showPastPeriod.salesReport" />

                                <span class="ms-2 text-sm text-gray-600">הצג תקופה קודמת</span>
                            </label>
                            <div class="hidden md:block bg-gray-100 h-full w-[1px] absolute left-1/2 top-0"></div>
                            <stat-box :stat="storeSales.totalSales"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      append-to-value="₪"
                                      icon-circle-class="bg-rose-200">
                                <template #icon>
                                    <icon-sale class="text-rose-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>
                            <stat-box :stat="storeSales.closeRate"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      append-to-value="%"
                                      icon-circle-class="bg-rose-200">
                                <template #icon>
                                    <icon-conversion class="text-rose-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>
                            <stat-box :stat="storeSales.totalSalesCount"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      icon-circle-class="bg-green-50">
                                <template #icon>
                                    <icon-box class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>
                            <stat-box :stat="storeSales.atv"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      append-to-value="₪"
                                      con-circle-class="bg-lime-200">
                                <template #icon>
                                    <icon-book class="text-lime-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>

                            <stat-box :stat="storeSales.itemsSold"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      icon-circle-class="bg-blue-50">
                                <template #icon>
                                    <icon-sold class="text-blue-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>

                            <stat-box :stat="storeSales.productPrice"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      append-to-value="₪"
                                      icon-circle-class="bg-amber-200">
                                <template #icon>
                                    <icon-price-tag class="text-amber-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>
                        </div>

                        <div class="mt-5 bg-white p-6 rounded-[10px] relative">
                            <age-gender-chart :age-data="storeData.ageData"
                                              :gender-data="storeData.genderData"
                            />
                        </div>
                    </div>
                    <div class="md:col-span-7">
                        <div class="h-full md:bg-white md:rounded-[10px]">
                            <div class="grid grid-cols-2 gap-5 mb-5 bg-white p-4 max-md:rounded-[10px] md:mb-0 md:rounded-t-[10px] md:grid-cols-4 md:gap-x-2 md:gap-y-5">
                                <label class="flex items-center col-span-2 md:col-span-4">
                                    <Checkbox name="toggle_past_period"
                                              v-model:checked="showPastPeriod.chartLegend" />

                                    <span class="ms-2 text-sm text-gray-600">הצג תקופה קודמת</span>
                                </label>

                                <chart-stat-box v-for="stat in lineChartHistory"
                                                :stat="stat"
                                                :show-past-period="showPastPeriod.chartLegend"
                                                :class="['max-md:w-full max-md:bg-gray-50 max-md:p-4 max-md:rounded-[5px]  ' , { 'max-md:last:w-1/2 max-md:last:col-span-2 max-md:mx-auto' : lineChartHistory.length % 2 === 1}]"
                                />
                            </div>

                            <div  class="w-full bg-white py-4 md:p-4 max-md:rounded-[10px] md:rounded-b-[10px] sm:rounded-t-0">
                            <apexchart
                                type="line"
                                :options="lineChart.chartOptions"
                                :series="lineChart.series"></apexchart>
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import {
    PdfLogo,
    IconBox,
    IconCalendar,
    IconConversion,
    IconPriceTag,
    IconPeople,
    IconArrowUp,
    IconArrowDown,
    IconSale,
    IconSold,
    IconBook,
    IconBags,
    IconWarning,
    StatBox,
    ChartStatBox,
    AgeGenderChart
} from '@/_vendor/eyez/index'
import moment from "moment";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { DatePicker } from 'v-calendar';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import JsonExcel from "vue-json-excel3";
import Checkbox from '@/Components/Checkbox.vue';
import html2canvas from 'html2canvas';

export default {
    name: "Home",
    components: {
        IconPeople,
        PdfLogo,
        IconBox,
        IconCalendar,
        IconConversion,
        IconPriceTag,
        IconArrowUp,
        IconArrowDown,
        IconSale,
        IconSold,
        IconBook,
        IconBags,
        IconWarning,
        StatBox,
        ChartStatBox,
        Dropdown,
        DropdownLink,
        DatePicker,
        PrimaryButton,
        SecondaryButton,
        AuthenticatedLayout,
        Head,
        JsonExcel,
        Checkbox,
        AgeGenderChart
    },
    props: {
        reportType: {
            type: String
        },
        storeData: {
            type: [Object, Array]
        },
        summary: {
            type: [Object, Array]
        },
        stores: {
            type: [Object, Array]
        },
        avgWalkIn: {
            type: Number,
        },
        currentStore: {
            type: [Object, Array, String]
        },
        prevStoreData: {
            type: [Object, Array]
        },
        storeSales: {
            type: [Object, Array]
        },
        roles: {
            type: Array
        },
        settings: {
            type: [Object, Array],
            default: {
                ageGroups: {
                    earlyYouth: 'Early Youth',
                    youth: 'Youth',
                    middleAge: 'Middle Aged',
                    elderly: 'Elderly'
                }
            },
        }
    },
    data() {
        return {
            exportHeaders: {
                "Date": "date",
                "Time": "time",
                "Walk-in Count": "walkInCount",
                "Sales": "salesTotal",
                "Total Items": "itemsCount",
                "Order q-ty": "ordersCount",
                "Close Rate(%)": "closeRate",
                "ATV": "atv"
            },
            showPastPeriod: {
                chartLegend: false,
                salesReport: false,
            },
            pickerRange: {
                start: this.storeData ? this.storeData?.dateFrom : new Date(),
                end: this.storeData ? this.storeData?.dateTo : new Date()
            },
            lineChart: {
                chartOptions: {
                    animations: {
                        enabled: false,
                    },
                    grid: {
                        show: true,
                        borderColor: '#E7E7E7',
                        strokeDashArray: 4,
                    },
                    colors: ['#1495FF', '#EF2A56', '#71D080'],
                    chart: {
                        toolbar: {
                            show: false
                        }
                    },
                    tooltip: {
                        enabled: true,
                        enabledOnSeries: false,
                        shared: true,
                        theme: 'dark',
                        fillSeriesColor: true,
                        style: {
                            fontSize: '12px',
                            fontFamily: undefined
                        },
                        x: {
                            show: true,
                            format: {
                                hour: 'HHmm'
                            },
                            formatter: (index) =>  {
                                let values = this.lineChartCategories();
                                // console.log(this.lineChartCategories())
                                if (index === 1) {
                                    return `until ${values[index -1]}`
                                } else {
                                    return `${values[index -1]} - ${values[index - 2]}`
                                }
                            },
                        },
                        y: {
                            formatter: undefined,
                            title: {
                                formatter: (seriesName) => '',
                            },
                        },
                        fixed: {
                            enabled: false,
                            position: 'topRight',
                            offsetX: 0,
                            offsetY: 0,
                        },
                    },
                    markers: {
                        size: [5,5],
                        colors: ['#fff', '#fff'],
                        strokeColors: ['#1495FF', '#EF2A56', '#71D080'],
                        strokeWidth: 1,
                        shape: "circle",
                        radius: 2,
                        showNullDataPoints: true,
                    },
                    stroke: {
                        show: true,
                        curve: 'smooth',
                        width: 1,
                        dashArray: 0,
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -5,
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 'regular',
                        },
                        background: {
                            enabled: false,
                        }
                    },
                    xaxis: {
                        labels: {
                            show: true, // Set this to false to hide Y-axis labels
                        },
                        categories: this.lineChartCategories()
                    },
                    yaxis: {
                        labels: {
                            show: false, // Set this to false to hide Y-axis labels
                        },
                    },
                    legend: {
                        show: true ,
                        fontFamily: 'Inter, Arial',
                        fontWeight: 500,
                        markers: {
                            width: 10,
                            height: 10,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            radius: 2,
                            offsetX: 5,
                            offsetY: 0
                        },
                    },
                    responsive: [
                        {
                            breakpoint: 768,
                            options: {
                                dataLabels: {
                                    style: {
                                        fontSize: '10px'
                                    }
                                },
                                xaxis: {
                                    labels: {
                                        style: {
                                            fontSize: '10px'
                                        }
                                    }
                                },
                                markers: {
                                    size: [3, 3]
                                }
                            }
                        }
                    ]
                },
                series: [
                    {
                        name: this.dateRangeText(),
                        data: this.lineChartArray(this.storeData.hourlyWalkIn),
                    },
                    {
                        name: "תקופה קודמת",
                        data: this.lineChartArray(this.prevStoreData.hourlyWalkIn),
                    }
                ]
            }
        }
    },
    methods: {
        saveImage(data) {
            // You can save the image data or display it as needed
            const link = document.createElement('a');
            link.href = data;
            link.download = 'eyezDocument.png';
            link.click();
        },
        printPage() {
            html2canvas(document.documentElement).then(canvas => {
                const imageData = canvas.toDataURL('image/png');
                this.saveImage(imageData);
            });
        },
        async fetchExportData() {
            let exportData = [];

            await axios.post(route('report.export', {
                store: this.currentStore.id !== undefined ? this.currentStore.id : this.currentStore
            }), {
                dateFrom: this.storeData?.dateFrom,
                dateTo: this.storeData?.dateTo,
            })
            .then(response => {
                let orders = response.data.orders;

                if (this.reportType === 'days' || this.currentStore.id !== undefined) {
                    const walkinByDate = this.storeData.hourlyWalkIn.reduce((accumulator, obj) => {
                        const key = obj.date;
                        if (!accumulator[key]) {
                            accumulator[key] = [];
                        }

                        accumulator[key].push(obj);
                        return accumulator;
                    }, {});
                    Object.keys(walkinByDate).forEach((date) => {
                        let dailyReport = this.excelMapRows(walkinByDate[date], orders, date);

                        exportData.push(...dailyReport);
                    })
                } else {
                    let selectedDate =  moment(this.storeData?.dateFrom).format('YYYY-MM-DD').toString();

                    exportData = this.excelMapRows(this.storeData.hourlyWalkIn, orders, selectedDate);
                }
            })

            return exportData
        },
        excelMapRows(dayilyWalkIn, orders, selectedDate) {
            let exportData = [];
            dayilyWalkIn.forEach(walkIn => {

                // Finding all orders matching date and time. e.g. all orders between 10:00 and 11:00 will be related to 11:00 in export file.
                let walkInHour = moment(`${walkIn.date} ${walkIn.time}`).hour()
                let matchingOrders = orders.filter(order => {
                    let orderDate = moment(order.order_date).format('YYYY-MM-DD')
                    let orderTime = moment(order.order_date).hour() + 1;

                    return walkInHour === orderTime && orderDate === walkIn.date
                })

                let totalSales = matchingOrders.reduce((accumulator, order) => {
                    return accumulator + order.order_total;
                }, 0);
                let itemsCount = matchingOrders.reduce((accumulator, order) => {
                    return accumulator + order.items_count;
                }, 0);

                let ordersCount = matchingOrders.length;
                let passengerFlow = dayilyWalkIn.filter(wi => walkIn.time === wi.time)
                                                .reduce((accumulator, wi) => {
                                                    return accumulator + wi.passengerFlow;
                                                }, 0)
                let excelRow = {
                    "storeName": "",
                    "date": selectedDate,
                    "time": walkIn.time,
                    "walkInCount": passengerFlow ?? walkIn.passengerFlow,
                    "salesTotal": totalSales,
                    "itemsCount": itemsCount,
                    "ordersCount": ordersCount,
                    "closeRate": (ordersCount > 0 ? parseFloat(ordersCount / walkIn.passengerFlow * 100).toFixed(1) : 0) + '%',
                    "atv": (ordersCount > 0 ? parseFloat(totalSales / ordersCount).toFixed(1) : 0),
                }

                let objectExist = exportData.find(obj => obj.time === walkIn.time && obj.date === selectedDate);

                if (!objectExist)
                    exportData.push(excelRow);
            })

            return exportData;
        },
        clearSummaryCache() {
            axios.post(route('summary.clear-cache'), {
                storeId: this.currentStore.dep_id ?? null,
            })
            .then(response => {
                alert(response.data.message);

                window.location.reload();
            })
        },
        onDateRangeChange(dateRange) {
            let endpoint = this.roles.includes('admin') ? 'profile.dashboard.view' : 'home.show'
            this.$inertia.visit(route(endpoint, {
                user: this.currentStore.user_id,
                stores: this.currentStore.dep_id !== undefined ? this.currentStore.dep_id : this.currentStore,
                dateFrom: moment(dateRange.start).format('YYYY-MM-DD'),
                dateTo: moment(dateRange.end).format('YYYY-MM-DD')
            }))
        },
        dateRangeText() {
            let dateTo = moment(this.storeData?.dateTo).subtract(2, 'hour').format('YYYY-MM-DD')
            let dateFrom = moment(this.storeData?.dateFrom).format('YYYY-MM-DD')
            if ( moment(dateFrom).isSame(moment(dateTo), 'day') ) {
                return moment(dateFrom).format('YYYY-MM-DD').toString()
            } else {
                return`${dateFrom} - ${dateTo}`
            }
        },
        percent(current, previous) {
            const difference = Math.abs(current - previous)
            return previous === 0 ? '100%' :( (difference / previous ) * 100).toFixed(1) + '%'
        },
        lineChartCategories() {
            this.prevStoreData.hourlyWalkIn = this.prevStoreData.hourlyWalkIn.map(obj => ({
                ...obj,
                time: obj.time === '00:00' ? '24:00' : obj.time
            }))

            this.storeData.hourlyWalkIn = this.storeData.hourlyWalkIn.map(obj => ({
                ...obj,
                time: obj.time === '00:00' ? '24:00' : obj.time
            }))
            return [...new Set([...this.storeData.hourlyWalkIn, ...this.prevStoreData.hourlyWalkIn].map(item => item.time))].sort()
        },
        lineChartArray(main) {
            return this.lineChartCategories().map(time => {
                return main.filter(obj => obj.time === time).reduce((accumulator, currentValue) => {
                    return accumulator + currentValue.passengerFlow;
                }, 0);
            })
        },
        ageGroupLabel(key) {
                return key === 'youth' ? this.settings?.ageGroups !== undefined ? this.settings?.ageGroups?.key : 'Youth'
                    : key === 'earlyYouth' ? this.settings?.ageGroups !== undefined ? this.settings?.ageGroups?.key : 'Teenagers'
                    : key === 'middleAge' ? this.settings?.ageGroups !== undefined ? this.settings?.ageGroups?.key : 'Middle Age'
                    : key === 'middleOld' ? this.settings?.ageGroups !== undefined ? this.settings?.ageGroups?.key : 'Middle Old'
                    : key === 'elderly' ? this.settings?.ageGroups !== undefined ? this.settings?.ageGroups?.key : 'Elderly'
                    : ''
        },
    },
    computed: {
        availableStores() {
            return this.stores.filter(store => {
                if (this.settings?.hiddenStores) {
                    return !this.settings?.hiddenStores?.includes(store.dep_id)
                } else {
                    return true
                }
            })
        },
        showAllStoresLink() {
            let updatedStores = this.stores.filter(store => {
                if (this.settings?.hiddenStores) {
                    return !this.settings?.hiddenStores?.includes(store.dep_id)
                } else {
                    return true
                }
            })

            return updatedStores.length > 1;
        },
        exportAgeGender() {
            let exportObject = {
                'נשים': this.storeData.genderData?.female?.count,
                'גברים': this.storeData.genderData?.male?.count
            };

            for (let key in this.storeData.ageData) {
                if (this.storeData.ageData.hasOwnProperty(key)) {
                    let ageGroup = this.storeData.ageData[key];
                    exportObject[this.ageGroupLabel(key)] = ageGroup.count
                }
            }

            return [
                exportObject
            ]
        },
        lineChartHistory() {
            const prevStoreData = this.prevStoreData.hourlyWalkIn;
            const currentStoreData = this.storeData.hourlyWalkIn;

            return this.lineChartCategories().map(time => {
                return {
                    current: {
                        title: time,
                        value: currentStoreData.filter(obj => obj.time === time).reduce((accumulator, currentValue) => {
                            return accumulator + currentValue.passengerFlow;
                        }, 0)
                    },
                    previous: {
                        title: 'יום אחרון',
                        value: prevStoreData.filter(obj => obj.time === time).reduce((accumulator, currentValue) => {
                            return accumulator + currentValue.passengerFlow;
                        }, 0)
                    }
                }
            })
        }
    }
}
</script>

<style>
.vue-apexcharts {
    display: flex;
    justify-content: center;
}
</style>
