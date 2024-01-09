<template>
    <div v-if="genderData.female || genderData.male" class="mb-8">
        <div class="flex gap-4">
            <div class="h-8 rounded-md bg-blue-400"
                 :style="`width: ${genderData.female?.percentage}%`" />

            <div class="h-8 rounded-md bg-green-400"
                 :style="`width: ${genderData.male?.percentage}%`" />
        </div>
        <div class="w-full h-[1px] bg-gray-separator my-4"></div>

        <div class="flex justify-center gap-8">
            <bar-stat-box :stat="genderData.female" color-class="text-blue-400" title="נשים">
                <template #icon>
                    <icon-female />
                </template>
            </bar-stat-box>

            <bar-stat-box :stat="genderData.male" color-class="text-green-400" title="גברים">
                <template #icon>
                    <icon-male />
                </template>
            </bar-stat-box>
        </div>
    </div>
    <div v-else class="text-center font-medium mb-8">No gender data found.</div>
    <div  v-if="Object.keys(ageData).length > 0"  class="">
        <div class="flex gap-2">
            <template v-if="ageData" v-for="(ageGroup, key) in ageData">
                <div v-if="ageGroup.percentage > 0"
                     :class="['h-8 rounded-md', ageGroupClass(key, 'bg')]"
                     :style="`width: ${ageGroup.percentage}%`" />
            </template>
            <div v-else class="bg-red-300 bg-blue-300 bg-blue-400 bg-purple-300 text-red-300 text-blue-300 text-blue-400 text-purple-300"></div>
        </div>

        <div class="w-full h-[1px] bg-gray-separator my-4"></div>

        <div class="flex justify-center gap-4 flex-wrap">
            <div v-for="(ageGroup, key) in ageData" class="basis-0 grow">
                <bar-stat-box v-if="ageGroup.count > 0"
                              :stat="ageGroup"
                              :color-class="ageGroupClass(key)"
                              :title="ageGroupLabel(key)">
                    <template #icon>
                        <icon-teenager v-if="key === 'earlyYouth'" />
                        <icon-youth v-if="key === 'youth'" />
                        <icon-middle-age v-if="key === 'middleAge'" />
                        <icon-middle-old v-if="key === 'middleOld'" />
                        <icon-elderly v-if="key === 'elderly'" />
                    </template>
                </bar-stat-box>
            </div>
        </div>
    </div>
    <div v-else class="text-center font-medium">No age data found.</div>
</template>

<script>
import {
    IconFemale,
    IconMale,
    IconYouth,
    IconTeenager,
    IconMiddleAge,
    IconMiddleOld,
    IconElderly,
    BarStatBox,
} from "@/_vendor/eyez/index"
import {usePage} from "@inertiajs/vue3";
export default {
    name: "AgeGenderChart",
    components: {
        IconFemale,
        IconMale,
        IconYouth,
        IconTeenager,
        IconMiddleAge,
        IconMiddleOld,
        IconElderly,
        BarStatBox
    },
    props: {
        ageData: {
            type: Object,
            default: {},
        },
        genderData: {
            type: Object,
            default: {},
        }
    },
    data() {
        return {
            settings: usePage().props.settings?.ageGroups
        }
    },
    methods: {
        ageGroupClass(key, prefix = 'text') {
            return prefix + '-' + (key === 'youth' ? 'green-300'
                                : key === 'earlyYouth' ? 'red-300'
                                : key === 'middleAge' ? 'blue-300'
                                : key === 'middleOld' ? 'blue-400'
                                : key === 'elderly' ? 'purple-300'
                                : 'gray-300')
        },
        ageGroupLabel(key) {
                return key === 'youth' ? this.settings?.key ?? 'Youth'
                    : key === 'earlyYouth' ? this.settings?.key ?? 'Teenagers'
                    : key === 'middleAge' ? this.settings?.key ?? 'Middle Age'
                    : key === 'middleOld' ? this.settings?.key ?? 'Middle Old'
                    : key === 'elderly' ? this.settings?.key ?? 'Elderly'
                    : 'gray-300'

        }
    }
}
</script>
