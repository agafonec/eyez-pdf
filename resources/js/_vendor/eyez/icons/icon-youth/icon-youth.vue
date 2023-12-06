<template>
    <svg v-bind="{width, height}" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9.25654 2.16528L10.3773 0H5.65405L6.77486 2.16528H9.25654Z" :fill="color"/>
        <path d="M9.18603 2.79758H6.84538L5.12746 12.5035C5.08853 12.7214 5.14744 12.9456 5.28882 13.116L7.42406 15.721C7.56971 15.8981 7.7869 16.0003 8.01605 16C8.24536 15.9996 8.46222 15.8965 8.60718 15.7191L10.7426 13.1112C10.8838 12.9427 10.9429 12.72 10.9039 12.5037L9.18603 2.79758Z" :fill="color"/>
    </svg>
</template>

<script>
export default {
    name: "icon-youth",
    props: {
        width: {
            type: [Number, String],
            default: 16,
        },
        height: {
            type: [Number, String],
            default: 16,
        },
        color: {
            type: String,
            default: 'currentColor',
        },
    }
}
</script>
