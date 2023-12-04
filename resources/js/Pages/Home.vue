<template>
    <Head title="נתוני זמן אמת" />

    <AuthenticatedLayout>
        <div ref="componentToPrint" class="p-5 max-w-pdf-container mx-auto" dir="rtl">
            <div class="text-center grid md:grid-cols-4 grid-cols-2 justify-center mb-2 gap-4 max-w-3xl mx-auto">
                <PrimaryButton class="w-full block justify-center h-full"
                               @click="cleareSummaryCache" >ריענון</PrimaryButton>
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
                            :data="[Object.assign({}, storeData.genderData, storeData.ageData)]">
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
                            <DropdownLink v-for="(store, index) in stores" :href="route('home.show', {stores: store.dep_id})" align="center">Eyez Store {{ index }}</DropdownLink>
                            <DropdownLink :href="route('home.show', {stores: stores.map(obj => obj.dep_id).join(',')})" align="center">All stores</DropdownLink>
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
                    <div class="bg-green-100 w-full md:w-1/3 pt-10 pb-4 sm:py-10 rounded-[10px] relative flex justify-center">
                        <div class="flex items-center flex-col pt-8 sm:pt-0 sm:flex-row">
                            <div class="flex items-center">
                                <icon-people class="text-green-400"/>
                                <span class="text-3xl mr-2.5 text-green-400 font-medium">{{ storeData.walkInCount.toLocaleString() }}</span>
                            </div>
                            <div v-if="this.reportType === 'hours'" class="flex items-center max-md:mt-4">
                                <span :class="['font-medium md:ms-6 text-lg', `${storeData.walkInCount < prevStoreData.walkInCount ? 'text-red-300' : 'text-green-300'}` ]">
                                    {{ percent(storeData.walkInCount, prevStoreData.walkInCount) }}
                                </span>
                                <div v-if="storeData.walkInCount < prevStoreData.walkInCount"
                                     class="flex items-center justify-center max-md:from-red-200 max-md:to-red-500 max-md:bg-gradient-to-t max-md:ms-3 rounded-full w-11 h-11 sm:w-auto sm:h-auto">
                                    <icon-arrow-down class="text-white sm:text-red-300 w-3 h-5 sm:w-2 sm:h-3.5"/>
                                </div>
                                <div v-else class="flex items-center justify-center max-md:from-green-200 max-md:to-green-500 max-md:bg-gradient-to-t max-md:ms-3 rounded-full w-11 h-11 sm:w-auto sm:h-auto">
                                    <icon-arrow-up class="text-white sm:text-green-300 w-3 h-5 sm:w-2 sm:h-3.5"/>
                                </div>
                            </div>
                            <span v-if="reportType === 'hours'"
                                  class="w-[90%] bg-green-300 text-white text-sm absolute rounded-md py-2 text-center top-4 left-4 md:py-0 md:top-2 md:left-2 md:bg-transparent md:text-green-300 md:w-auto">
                                חנות AVG: {{ avgWalkIn }}
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
                                      icon-circle-class="bg-green-50">
                                <template #icon>
                                    <icon-people class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>

                            <stat-box :stat="storeSales.productPrice"
                                      mobile-direction="column"
                                      :show-last-period="reportType === 'hours' && showPastPeriod.salesReport"
                                      :show-difference="reportType === 'hours'"
                                      append-to-value="₪"
                                      icon-circle-class="bg-green-50">
                                <template #icon>
                                    <icon-people class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                                </template>
                            </stat-box>
                        </div>

                        <div class="mt-5">

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
                    <div class="md:col-span-7 bg-white p-4 rounded-[10px] relative">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:h-full md:flex md:items-center md:pb-[25%]">
                            <apexchart width="350" height="350" type="donut" :options="pieChartAge.chartOptions" :series="pieChartAge.series"></apexchart>
                            <apexchart  width="350" height="330" type="donut" :options="pieChartGender.chartOptions" :series="pieChartGender.series"></apexchart>
                        </div>
                        <div class="max-md:text-xs bg-gray-50 text-gray-200 rounded-md p-5 md:absolute md:right-5 md:bottom-5 grid grid-cols-2 gap-10 align-end">
                            <div class="text-start md:text-center">
                                <div>בני נוער בגילאי <span>0 - 15</span></div>
                                <div>נוער <span>16 - 40</span> שנים</div>
                                <div class="text-xs text-black font-semibold md:hidden mt-2">
                                    <span>תאריך:</span>
                                    <span v-html="dateRangeText()"></span>
                                </div>
                            </div>

                            <div>
                                <div>גיל הביניים <span>40 - 60</span> שנים</div>
                                <div>מבוגרים <span>60+</span> - ריק</div>
                            </div>
                        </div>
                        <div class="max-md:hidden bg-gray-50 px-4 py-2 rounded-md font-semibold absolute left-5 bottom-5">
                            <span>תאריך:</span>
                            <span  v-html="dateRangeText()"></span>
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
    IconPeople,
    IconArrowUp,
    IconArrowDown,
    IconSale,
    IconBook,
    IconBags,
    IconWarning,
    StatBox,
    ChartStatBox
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

let donutSettings = {
    chart: {
        width: 300,
        type: 'donut',
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            },
            startAngle: 0,
            endAngle: 360
        }
    },
    dataLabels: {
        enabled: true,
        enabledOnSeries: true,
        formatter: function (val, opts) {
            return val.toFixed(1) + '%'
        },
        style: {
            fontSize: '12px',
            fontFamily: 'Inter, Arial, sans-serif',
            fontWeight: 'bold',
            colors: undefined
        },
        background: {
            enabled: true,
            foreColor: '#fff',
            padding: 4,
            borderRadius: 2,
            borderWidth: 1,
            borderColor: '#fff',
            opacity: 0.9,
        },
    },
    fill: {
        type: 'gradient',
    },
    legend: {
        position: 'bottom',
        formatter: function(seriesName, opts) {
            return [seriesName, " - ", opts.w.globals.series[opts.seriesIndex]]
        },
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
    }
}

export default {
    name: "Home",
    components: {
        IconPeople,
        PdfLogo,
        IconBox,
        IconCalendar,
        IconConversion,
        IconArrowUp,
        IconArrowDown,
        IconSale,
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
        Checkbox
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
        settings: {
            type: [Object, Array],
            default: {
                earlyYouth: 'Early Youth',
                youth: 'Youth',
                middleAge: 'Middle Aged',
                elderly: 'Elderly'
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
            lineChartLegend: [],
            pieChartAge: {
                series: Object.values(this.storeData.ageData),
                chartOptions: {
                    ...donutSettings,
                    colors: ['#00E396', '#008FFB', '#FEB019', '#FF4560'],
                    labels: this.settings?.ageGroups !== undefined ? Object.values(this.settings?.ageGroups) : ['Early Youth','Youth','Middle Aged','Elderly'],
                }
            },
            pieChartGender: {
                series: Object.values(this.storeData.genderData),
                chartOptions: {
                    ...donutSettings,
                    labels: Object.keys(this.storeData.genderData),
                },
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
                let excelRow = {
                    "storeName": "",
                    "date": selectedDate,
                    "time": walkIn.time,
                    "walkInCount": walkIn.passengerFlow,
                    "salesTotal": totalSales,
                    "itemsCount": itemsCount,
                    "ordersCount": ordersCount,
                    "closeRate": (ordersCount > 0 ? parseFloat(ordersCount / walkIn.passengerFlow * 100).toFixed(1) : 0) + '%',
                    "atv": (ordersCount > 0 ? parseFloat(totalSales / ordersCount).toFixed(1) : 0),
                }
                exportData.push(excelRow);
            })

            return exportData;
        },
        cleareSummaryCache() {
            axios.post(route('summary.clear-cache'), {
                storeId: this.currentStore.dep_id ?? null,
            })
            .then(response => {
                alert(response.data.message);

                window.location.reload();
            })
        },
        onDateRangeChange(dateRange) {
            console.log(this.currentStore.dep_id,  this.currentStore)
            this.$inertia.visit(route('home.show', {
                stores: this.currentStore.dep_id !== undefined ? this.currentStore.dep_id : this.currentStore,
                dateFrom: moment(dateRange.start).format('YYYY-MM-DD'),
                dateTo: moment(dateRange.end).format('YYYY-MM-DD')
            }))
        },
        dateRangeText() {
            let dateTo = moment(this.storeData?.dateTo).subtract(2, 'hour').format('YYYY-MM-DD')
            let dateFrom = moment(this.storeData?.dateFrom).format('YYYY-MM-DD')
            if ( moment(dateFrom).isSame(moment(dateTo).subtract(1, 'hour'), 'day') ) {
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
            return [...new Set([...this.storeData.hourlyWalkIn, ...this.prevStoreData.hourlyWalkIn].map(item => item.time))].sort()
        },
        lineChartArray(main) {
            return this.lineChartCategories().map(time => {
                return main.filter(obj => obj.time === time).reduce((accumulator, currentValue) => {
                    return accumulator + currentValue.passengerFlow;
                }, 0);
            })
        }
    },
    computed: {
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
