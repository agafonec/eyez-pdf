<template>
    <svg v-bind="{ width, height }"  viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 15.2727C0 15.6744 0.325624 16 0.727273 16H15.2727C15.6744 16 16 15.6744 16 15.2727V5.81818H0V15.2727Z" :fill="color"/>
        <path d="M15.2727 1.45455H11.1515V0.727273C11.1515 0.325624 10.8259 0 10.4242 0C10.0226 0 9.69697 0.325624 9.69697 0.727273V1.45455H6.30303V0.727273C6.30303 0.325624 5.97741 0 5.57576 0C5.17411 0 4.84848 0.325624 4.84848 0.727273V1.45455H0.727273C0.325624 1.45455 0 1.78017 0 2.18182V4.36364H16V2.18182C16 1.78017 15.6744 1.45455 15.2727 1.45455Z" :fill="color"/>
    </svg>
</template>

<script>
export default {
    name: "IconCalendar",
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

<style scoped>

</style>
