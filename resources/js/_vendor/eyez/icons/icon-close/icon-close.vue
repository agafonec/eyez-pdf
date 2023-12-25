<template>
    <i class="ico-svg-close">
        <svg v-bind="{ width, height }" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">
            <path d="m25 512a25 25 0 0 1 -17.68-42.68l462-462a25 25 0 0 1 35.36 35.36l-462 462a24.93 24.93 0 0 1 -17.68 7.32z" :fill="color"></path>
            <path d="m487 512a24.93 24.93 0 0 1 -17.68-7.32l-462-462a25 25 0 0 1 35.36-35.36l462 462a25 25 0 0 1 -17.68 42.68z" :fill="color"></path>
        </svg>
    </i>

</template>

<script>
export default {
    name: "IconClose",
    props: {
        width: {
            type: Number,
            default: 15,
        },

        height: {
            type: Number,
            default: 15,
        },

        color: {
            type: String,
            default: 'currentColor',
        },
    },
}
</script>
