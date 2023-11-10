<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    align: {
        type: String,
        default: 'start'
    }
});
</script>

<template>
    <Link
        :href="href"
        :class="[
            'block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out',
            `${align === 'start' ? 'text-start' : align === 'center' ? 'text-center' : 'text-end'}`
        ]"
    >
        <slot />
    </Link>
</template>
