<template>
    <div class="group relative cursor-pointer">
        <div class="text-center">
            <div :class="['flex items-center justify-center mb-2', colorClass]">
                <div class="ml-2">
                    <slot name="icon" />
                </div>
                <span class="font-semibold">{{ title }}</span>
            </div>
            <div class="flex items-center justify-center">
                <span class="text-2xl ml-2 font-semibold">{{ stat.count }}</span>
                <span class="text-md text-gray-300">איש</span>
            </div>
            <div class="text-md text-gray-300">{{ stat.percentage }}%</div>
        </div>
        <div role="tooltip"
             :class="['absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap',
              'text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700',
              'group-hover:opacity-100 group-hover:visible'
              ]">
            {{ title }}: {{ stat.description }}
            <div class="tooltip-arrow" data-popper-arrow></div>
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
        }
    }
}
</script>
