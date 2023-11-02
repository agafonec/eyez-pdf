<template>
    <div>
        <div class="flex leading-tight items-center text-sm">
            <div class="ml-1">{{ stat.current.title }}</div>
            <div>{{ stat.current.value }}</div>
            <div class="mx-1">-</div>
            <div :class="['flex', `${stat.current.value > stat.previous.value ? 'text-green-300' : 'text-red-300'}`]">
                <span class="font-medium leading-none text-xs">{{ percent }}</span>
                <icon-arrow-up v-if="stat.current.value > stat.previous.value" calss="text-green-300"/>
                <icon-arrow-down v-else class="text-red-300"/>
            </div>
        </div>
        <div class="text-gray-200 text-xs leading-tight flex">
            <div>{{ stat.previous.title }}</div>
            <div class="mx-1">-</div>
            <div>{{ stat.previous.value }}</div>
        </div>
    </div>
</template>

<script>
import {
    IconArrowDown,
    IconArrowUp
} from '@/_vendor/eyez/index'

export default {
    name: "ChartStatBox",
    components: {
        IconArrowDown,
        IconArrowUp
    },
    props: {
        stat: {
            type: Object,
            default: {
                current: {
                    title: '9:00',
                    value: 79
                },
                previous: {
                    title: 'יום אחרון',
                    value: 83
                }
            }
        }
    },
    computed: {
        percent() {
            const difference = Math.abs(this.stat.current.value - this.stat.previous.value)
            return ( (difference / this.stat.current.value  ) * 100).toFixed(0) + '%'
        }
    }
}
</script>
