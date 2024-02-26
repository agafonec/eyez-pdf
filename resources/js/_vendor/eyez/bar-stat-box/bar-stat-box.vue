<template>
    <div class="group relative cursor-pointer">
        <div class="text-center">
            <div :class="['flex items-center justify-center mb-2 whitespace-nowrap', colorClass]">
                <div class="ml-2">
                    <slot name="icon" />
                </div>
                <span class="font-semibold">{{ title }}</span>
            </div>
            <div v-if="!hideDescription" class="text-md text-gray-300">{{ stat.description }}</div>
            <div class="text-center">
                <span class="text-2xl font-semibold">{{ stat.count }}</span>
            </div>
            <div class="text-md text-gray-300">{{ stat.percentage }}%</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "bar-stat-box",
    props: {
        stat: {
            type: Object,
            required: true,
        },
        title: {
            type: String,
            default: 'Title'
        },
        colorClass: {
            type: String,
        },
        hideDescription: {
            type: Boolean
        }
    }
}
</script>
