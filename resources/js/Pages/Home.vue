<template>

    <div class="p-5 max-w-pdf-container mx-auto" dir="rtl">
        <div class="bg-gradient-to-r from-green-200 to-green-500 text-white p-4 md:p-8 rounded-[10px] relative flex flex-col md:flex-row items-center justify-center md:justify-between">
            <img src="images/logo.png" class="w-[100px] md:w-[225px] h-[36px] md:h-[81px] object-contain" alt="">
            <div class="text-3xl font-semibold uppercase">
                <Dropdown align="center">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="inline-flex items-center bg-transparent
                                text-xl md:text-3xl font-semibold uppercase hover:text-gray-700 focus:outline-none transition"
                            >
                                {{ storeNameC }}
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('home.show', {store: storeName})" align="center"> {{ storeName }} </DropdownLink>
                        <DropdownLink :href="route('home.show', {store: 'Other Store Name'})" align="center"> Other Store Name </DropdownLink>
                    </template>
                </Dropdown>
            </div>
            <div class="end-4 top-4 md:top-0 md:end-0 absolute flex items-center text-white md:relative ">
                <div class="hidden md:block" v-html="dateRange()"></div>
                <icon-calendar class="mr-4" color="#ffffff"/>
            </div>
        </div>
        <div class="py-5 md:p-5">
            <div class="mb-0 md:mb-5 bg-white p-4 rounded-t-[10px] sm:rounded-[10px] flex items-center justify-center flex-col-reverse md:flex-row gap-5 md:gap-10">
                <div class="bg-green-100 w-full md:w-1/3 pt-10 pb-4 sm:py-10 rounded-[10px] relative flex justify-center">
                    <div class="flex items-center flex-col pt-8 sm:pt-0 sm:flex-row">
                        <div class="flex items-center">
                            <icon-people class="text-green-400"/>
                            <span class="text-3xl mr-2.5">היום:</span>
                            <span class="text-3xl mr-2.5 text-green-400 font-medium">{{ storeData.walkInCount }}</span>
                        </div>
                        <div class="flex items-center max-md:mt-4">
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
                        <span class="w-[90%] bg-green-300 text-white text-sm absolute rounded-md py-2 text-center top-4 left-4 md:py-0 md:top-2 md:left-2 md:bg-transparent md:text-green-300 md:w-auto">חנות AVG: {{ avarage(storeData.walkInCount, prevStoreData.walkInCount) }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap md:flex-nowrap items-center justify-between w-full md:w-2/3">
                    <stat-box variant="big" icon-circle-class="bg-lime-200">
                        <template #icon>
                            <icon-people width="32" height="32" class="text-lime-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                        </template>
                    </stat-box>
                    <stat-box variant="big" icon-circle-class="bg-amber-200">
                        <template #icon>
                            <icon-people width="32" height="32" class="text-amber-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                        </template>
                    </stat-box>
                    <stat-box class="mx-auto md:mx-0 mt-4 md:mt-0" variant="big" icon-circle-class="bg-green-50">
                        <template #icon>
                            <icon-people width="32" height="32" class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                        </template>
                    </stat-box>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 justify-center">
                <div class="md:col-span-5">
                    <div class="grid grid-cols-2 gap-x-5 gap-y-3 relative bg-white p-4 rounded-b-[10px] md:gap-x-10 md:rounded-[10px]">
                        <div class="hidden md:block bg-gray-100 h-full w-[1px] absolute left-1/2 top-0"></div>
                        <stat-box icon-circle-class="bg-lime-200">
                            <template #icon>
                                <icon-book class="text-lime-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-rose-200">
                            <template #icon>
                                <icon-sale class="text-rose-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-green-50">
                            <template #icon>
                                <icon-people class="text-green-500 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-amber-200">
                            <template #icon>
                                <icon-bags class="text-amber-400 w-[22px] h-[22px] md:w-[32px] md:h-[32px]"/>
                            </template>
                        </stat-box>
                    </div>

                    <div class="mt-5">
                        <div class="grid grid-cols-2 gap-5 mb-5 bg-white p-4 max-md:rounded-[10px] md:mb-0 md:rounded-t-[10px] md:grid-cols-4 md:gap-x-2 md:gap-y-5">
                            <chart-stat-box v-for="stat in lineChartHistory"
                                            :stat="stat"
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
                            <icon-warning class="max-md:hidden mx-auto mb-3"/>
                            <div>בני נוער בגילאי <span>0 - 15</span></div>
                            <div>נוער <span>16 - 40</span> שנים</div>
                            <div class="text-xs text-black font-semibold md:hidden mt-2">
                                <span>יְוֹם:</span>
                                <span v-html="dateRange()"></span>
                            </div>
                        </div>

                        <div class="md:pt-10">
                            <div>גיל הביניים <span>40 - 60</span> שנים</div>
                            <div>קשיש בן <span>60+</span> - ריק</div>
                        </div>
                    </div>
                    <div class="max-md:hidden bg-gray-50 px-4 py-2 rounded-md font-semibold absolute left-5 bottom-5">
                        <span>יְוֹם:</span>
                        <span  v-html="dateRange()"></span>
                    </div>
                </div>
            </div>
        </div>
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
    IconWarning,
    StatBox,
    ChartStatBox
} from '@/_vendor/eyez/index'
import moment from "moment";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

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
        IconCalendar,
        IconArrowUp,
        IconArrowDown,
        IconSale,
        IconBook,
        IconBags,
        IconWarning,
        StatBox,
        ChartStatBox,
        Dropdown,
        DropdownLink
    },
    props: {
        storeName: {
            type: String
        },
        storeData: {
            type: [Object, Array]
        },
        prevStoreData: {
            type: [Object, Array]
        }
    },
    data() {
        return {
            pieChartAge: {
                series: Object.values(this.storeData.ageData),
                chartOptions: {
                    ...donutSettings,
                    labels: Object.keys(this.storeData.ageData),
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
                        enabled: false,
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
                        // categories: ['9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
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
                        name: this.dateRange(),
                        data: this.lineChartArray(this.storeData.hourlyWalkIn),
                    },
                    {
                        name: "Previous period",
                        data: this.lineChartArray(this.prevStoreData.hourlyWalkIn),
                    }
                ]
            }
        }
    },
    methods: {
        dateRange() {
            console.log('Date Range', moment(this.storeData?.dateTo).format('YYYY-MM-DD'))
            if ( moment(this.storeData?.dateTo).isSame(moment(this.storeData?.dateFrom), 'day') ) {
                return moment(this.storeData?.dateTo).format('YYYY-MM-DD').toString()
            } else {
                return moment(this.storeData?.dateFrom).format('YYYY-MM-DD') + ' - ' + moment(this.storeData?.dateTo).format('YYYY-MM-DD')
            }
        },
        percent(current, previous) {
            const difference = Math.abs(current - previous)
            return ( (difference / previous ) * 100).toFixed(1) + '%'
        },
        avarage(current, previous) {
            return Math.floor( (current + previous) / 2 )
        },
        lineChartCategories() {
            return this.storeData.hourlyWalkIn.map((obj) => obj.time).concat(
                this.prevStoreData.hourlyWalkIn.map((obj) => obj.time).filter((item) => {
                    return this.storeData.hourlyWalkIn.findIndex((obj) => obj.time === item) === -1
                })
            )
        },
        lineChartArray(main) {
            return this.lineChartCategories().map(time => {
                return main.find(obj => obj.time === time)?.passengerFlow || 0;
            })
        }
    },
    computed: {
        storeNameC() {
            const urlParams = new URLSearchParams(window.location.search);

            return urlParams.has('store') ? urlParams.get('store') : this.storeName
        },
        lineChartHistory() {
            const prevStore = this.prevStoreData.hourlyWalkIn;
            // this.lineChartCategories().map(time => {
            //     return main.find(obj => obj.time === time)?.passengerFlow;
            // })
            return this.storeData.hourlyWalkIn.map((obj) => {
                let prevData = prevStore.find((prevObj) => obj.time == prevObj.time)
                    // console.log(prevData, obj)
                    return {
                        current: {
                            title: obj.time,
                            value: obj.passengerFlow
                        },
                        previous: {
                            title: 'יום אחרון',
                            value: prevData?.passengerFlow || 0
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
