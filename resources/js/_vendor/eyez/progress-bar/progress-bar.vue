<template>
    <div>
        <div v-if="label" class="flex justify-between mb-1">
            <span class="text-base font-medium text-black">{{ label}}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-green-400 h-4 flex items-center justify-center rounded-full transition-all" :style="`width: ${completed}%`"><span class="text-sm font-medium text-white">{{ completed }}%</span></div>
        </div>
        <div class="">
            <p v-if="completed >= 100">Processed: </p>
            <ul>
                <li class="text-green-400"><span>Success: </span><span class="font-semibold">{{ success }}</span></li>
                <li class="text-red-400"><span>Failed: </span><span class="font-semibold">{{ failed }}</span></li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProgressBar",
    props: {
        completed: {
            type: Number,
            default: 0
        },
        success: {
            type: Number,
            default: 0
        },
        failed: {
            type: Number,
            default: 0
        },
        label: String
    }
}
</script>
