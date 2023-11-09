<template>

    <div class="p-5 max-w-pdf-container mx-auto" dir="rtl">
        <div class="bg-gradient-to-r from-green-200 to-green-500 text-white flex items-center justify-between shadow-head p-8 rounded-[10px]">
            <pdf-logo />
            <div class="text-3xl font-semibold uppercase ">{{ storeName }}</div>
            <div class="flex items-center text-white">
                <span>{{ dateRange }}</span>
                <icon-calendar class="mr-4" color="#ffffff"/>
            </div>
        </div>
        <div class="p-5">
            <div class="bg-white p-4 rounded-[10px] flex justify-center">
                <div class="bg-green-100 max-w-md w-full py-10 rounded-[10px] relative flex justify-center">
                    <div class="flex items-center">
                        <icon-people class="text-green-400"/>
                        <span class="text-3xl mr-2.5">היום:</span>
                        <span class="text-3xl mr-2.5 text-green-400 font-medium">{{ storeData.walkInCount }}</span>
                        <div class="flex items-center">
                            <span :class="['font-medium mr-6 text-lg', `${storeData.walkInCount < prevStoreData.walkInCount ? 'text-red-300' : 'text-green-300'}` ]">
                                {{ percent(storeData.walkInCount, prevStoreData.walkInCount) }}
                            </span>
                            <icon-arrow-down v-if="storeData.walkInCount < prevStoreData.walkInCount" class="text-red-300"/>
                            <icon-arrow-up v-else class="text-green-300"/>
                        </div>
                        <span class="text-green-300 text-sm absolute top-2 left-2">חנות AVG: {{ avarage(storeData.walkInCount, prevStoreData.walkInCount) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-5 justify-center mt-5">
                <div class="col-span-5">
                    <div class="grid grid-cols-2 gap-x-10 gap-y-5 relative bg-white p-4 rounded-[10px]">
                        <div class="bg-gray-100 h-full w-[1px] absolute left-1/2 top-0"></div>
                        <stat-box icon-circle-class="bg-lime-200">
                            <template #icon>
                                <icon-book class="text-lime-400"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-rose-200">
                            <template #icon>
                                <icon-sale class="text-rose-400"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-green-50">
                            <template #icon>
                                <icon-people class="text-green-500"/>
                            </template>
                        </stat-box>
                        <stat-box icon-circle-class="bg-amber-200">
                            <template #icon>
                                <icon-bags class="text-amber-400"/>
                            </template>
                        </stat-box>
                    </div>

                    <div class="bg-white p-4 rounded-[10px] mt-5">
                        <div class="grid grid-cols-4 gap-x-2 gap-y-5">
                            <chart-stat-box v-for="stat in lineChartHistory" :stat="stat" />
                        </div>
                        <apexchart class="w-full" type="line" :options="lineChart.chartOptions" :series="lineChart.series"></apexchart>
                    </div>

                </div>
                <div class="col-span-7 bg-white p-4 rounded-[10px] relative">
                    <div class="grid grid-cols-2 gap-5">
                        <apexchart width="400" height="400" type="donut" :options="pieChartAge.chartOptions" :series="pieChartAge.series"></apexchart>
                        <apexchart  width="400" height="380" type="donut" :options="pieChartGender.chartOptions" :series="pieChartGender.series"></apexchart>

                    </div>
                    <div class="bg-gray-50 text-gray-200 rounded-md p-5 absolute right-5 bottom-5 grid grid-cols-2 gap-10 align-end">
                        <div class="text-center">
                            <icon-warning class="mx-auto mb-3"/>
                            <div>בני נוער בגילאי <span>0 - 15</span></div>
                            <div>נוער <span>16 - 40</span> שנים</div>
                        </div>

                        <div class="pt-10">
                            <div>גיל הביניים <span>40 - 60</span> שנים</div>
                            <div>קשיש בן <span>60+</span> - ריק</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 rounded-md font-semibold absolute left-5 bottom-5">
                        <span>יְוֹם:</span>
                        <span>{{ dateRange }}</span>
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
        ChartStatBox
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
                        show: false ,
                    }
                },
                series: [
                    {
                        name: this.dateRange,
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
        dateRange() {
            if ( moment(this.storeData?.dateTo).isSame(moment(this.storeData?.dateFrom), 'day') ) {
                return moment(this.storeData?.dateTo).format('YYYY-MM-DD')
            } else {
                return moment(this.storeData?.dateFrom).format('YYYY-MM-DD') + ' - ' + moment(this.storeData?.dateTo).format('YYYY-MM-DD')
            }
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
