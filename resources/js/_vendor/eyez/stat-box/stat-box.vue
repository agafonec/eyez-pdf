<template>
    <div v-if="variant === 'small'" :class="['flex items-center md:flex-row w-full relative', `${mobileDirection === 'row' ? 'flex-row': 'max-md:flex-col max-md:text-center'}`]">
        <div v-if="showLastPeriod" :class="['text-sm md:text-base absolute end-0 top-1 flex items-center', `${stat.current.value >= stat.previous.value ? 'text-green-300' : 'text-red-300'}`]">
            <span class="font-medium leading-none">{{ percent }}</span>
            <icon-arrow-up v-if="stat.current.value >= stat.previous.value" calss="text-green-300"/>
            <icon-arrow-down v-else class="text-red-300"/>
        </div>

        <div :class="['rounded-full w-[50px] h-[50px] md:w-[56px] md:h-[56px] flex items-center justify-center', iconCircleClass]">
            <slot name="icon" />
        </div>
        <div :class="['md:ms-4', mobileDirection === 'column' ? 'mt-2 md:mt-0 ms-0' : 'ms-2' ]">
            <div v-if="stat.current" :class="['mb-2', mobileDirection === 'column' ? 'max-md:flex max-md:flex-col-reverse max-md:text-center max-md:items-center' : '']">
                <div class="text-base md:text-xl text-gray-900 leading-tight flex">{{ stat.current.value.toLocaleString() }}{{ appendToValue }}</div>
                <div class="text-sm text-gray-300 leading-tight">{{ stat.current.title }}</div>
            </div>
            <div v-if="stat.previous && showLastPeriod" :class="[mobileDirection === 'column' ? 'max-md:flex max-md:flex-col-reverse max-md:text-center max-md:items-center' : '']">
                <div class="text-base md:text-xl text-gray-900 leading-tight">{{ stat.previous.value.toLocaleString() }}{{ appendToValue }}</div>
                <div class="text-sm text-gray-300 leading-tight">{{ stat.previous.title }}</div>
            </div>
        </div>
    </div>
    <div v-else-if="variant === 'big'" class="flex items-center relative">

        <div :class="['rounded-full w-[50px] h-[50px] md:w-[70px] md:h-[70px]  flex items-center justify-center', iconCircleClass]">
            <slot name="icon" />
        </div>
        <div class="ms-2 md:ms-4">
            <div v-if="stat.current" class="mb-1.5 md:mb-3">
                <div class="text-sm text-gray-300 leading-tight">{{ stat.current.title }}</div>
                <div class="text-base md:text-2xl text-gray-900 leading-tight flex">
                    <div>{{ stat.current.value.toLocaleString() }}{{ appendToValue }}</div>
                    <div v-if="showLastPeriod" :class="['text-sm md:text-base mr-2 flex items-center', `${stat.current.value >= stat.previous.value ? 'text-green-300' : 'text-red-300'}`]">
                        <span class="font-medium leading-none">{{ percent }}</span>
                        <icon-arrow-up v-if="stat.current.value >= stat.previous.value" calss="text-green-300"/>
                        <icon-arrow-down v-else class="text-red-300"/>
                    </div>
                </div>
            </div>
            <div v-if="stat.previous && showLastPeriod">
                <div class="text-sm text-gray-300 leading-tight">{{ stat.previous.title }}</div>
                <div class="text-base md:text-2xl text-gray-900 leading-tight">{{ stat.previous.value.toLocaleString() }}{{ appendToValue }}</div>
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
        variant: {
            type: String,
            default: 'small'
        },
        iconCircleClass: {
            type: String,
            default: 'bg-gray-100',
        },
        showLastPeriod: {
            type: Boolean,
            default: true,
        },
        mobileDirection: {
            type: String,
            default: 'row'
        },
        appendToValue: {
            type: String,
        },
        stat: {
            type: Object,
            default: {
                current: {
                    title: 'לא זמין',
                    value: 0
                },
                previous: {
                    title: '11/05/2023',
                    value: 0
                }
            }
        }
    },
    computed: {
        percent() {
            const difference = Math.abs(this.stat.current.value - this.stat.previous.value)
                return this.stat.previous.value === 0 ? '100%' : Math.round( (difference / this.stat.previous.value  ) * 100) + '%'
        }
    }
}
</script>

<style scoped>

</style>
