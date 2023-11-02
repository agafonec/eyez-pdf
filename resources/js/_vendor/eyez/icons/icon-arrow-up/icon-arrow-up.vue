<template>
    <svg v-bind="{width, height}" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
        <path d="M3.5 12.5C3.5 12.7761 3.72386 13 4 13C4.27614 13 4.5 12.7761 4.5 12.5L3.5 12.5ZM4.35355 0.146446C4.15829 -0.0488157 3.84171 -0.0488157 3.64645 0.146446L0.464466 3.32843C0.269204 3.52369 0.269204 3.84027 0.464466 4.03553C0.659728 4.2308 0.976311 4.2308 1.17157 4.03553L4 1.20711L6.82843 4.03553C7.02369 4.2308 7.34027 4.2308 7.53553 4.03553C7.7308 3.84027 7.7308 3.52369 7.53553 3.32843L4.35355 0.146446ZM4.5 12.5L4.5 0.5L3.5 0.5L3.5 12.5L4.5 12.5Z" :fill="color"/>
    </svg>
</template>

<script>
export default {
    name: "IconArrowDown",
    props: {
        width: {
            type: [Number, String],
            default: 8,
        },
        height: {
            type: [Number, String],
            default: 13,
        },
        color: {
            type: String,
            default: 'currentColor',
        },
    }
}
</script>

<style scoped>

</style>
