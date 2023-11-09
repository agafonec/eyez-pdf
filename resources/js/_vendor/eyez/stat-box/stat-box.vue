<template>
    <div class="flex items-center w-full relative">
        <div :class="['absolute left-0 top-0 flex items-center', `${stat.current.value > stat.previous.value ? 'text-green-300' : 'text-red-300'}`]">
            <span class="font-medium leading-none">{{ percent }}</span>
            <icon-arrow-up v-if="stat.current.value > stat.previous.value" calss="text-green-300"/>
            <icon-arrow-down v-else class="text-red-300"/>
        </div>

        <div :class="['rounded-full w-[56px] h-[56px] flex items-center justify-center', iconCircleClass]">
            <slot name="icon" />
        </div>
        <div class="mr-4">
            <div v-if="stat.current" class="mb-3">
                <div class="text-xl text-gray-900 leading-tight flex">{{ stat.current.value }}</div>
                <div class="text-sm text-gray-300 leading-tight">{{ stat.current.title }}</div>
            </div>
            <div v-if="stat.previous">
                <div class="text-xl text-gray-900 leading-tight">{{ stat.previous.value }}</div>
                <div class="text-sm text-gray-300 leading-tight">{{ stat.previous.title }}</div>
            </div>
        </div>
    </div>

</template>

<script>
import {
    IconArrowUp,
    IconArrowDown,
} from '@/_vendor/eyez/index';

export default {
    name: "StatBox",
    components: {
        IconArrowUp,
        IconArrowDown
    },
    props: {
        iconCircleClass: {
            type: String,
            default: 'bg-gray-100',
        },
        stat: {
            type: Object,
            default: {
                current: {
                    title: 'לא זמין',
                    value: 1234
                },
                previous: {
                    title: '11/05/2023',
                    value: 1956
                }
            }
        }
    },
    computed: {
        percent() {
            const difference = Math.abs(this.stat.current.value - this.stat.previous.value)
            return ( (difference / this.stat.previous.value  ) * 100).toFixed(1) + '%'
        }
    }
}
</script>

<style scoped>

</style>
