<script setup>
defineProps({
    message: {
        type: [String, Array],
    },
});
</script>

<template>
    <div v-show="message">
        <p v-if="Array.isArray(message)"
           v-for="m in message"
           class="text-sm text-red-600">
            {{ m }}
        </p>
        <p v-else>{{ message }}</p>
    </div>
</template>
