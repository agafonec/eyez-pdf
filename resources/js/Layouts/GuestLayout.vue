<script setup>
import { Link } from '@inertiajs/vue3';
import {
    PdfLogo
} from '@/_vendor/eyez/index'
</script>

<template>
    <div class="min-h-screen flex flex-col md:justify-center items-center pt-6 md:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <pdf-logo />
            </Link>
        </div>

        <div
            class="w-full md:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden md:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
