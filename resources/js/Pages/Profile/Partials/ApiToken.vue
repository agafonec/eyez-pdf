<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Generate API token</h2>

        <p class="mt-1 text-sm text-gray-600">
            You need to generate api token in order to make api requests. Add it to Bearer Authorization header.
        </p>
    </header>

    <div class="mt-6">
        <InputLabel for="apiToken" value="Api Token" />
        <TextInput
            id="apiToken"
            type="text"
            class="mt-1 block w-full"
            readonly
            :modelValue="apiToken"
            :value="apiToken"
        />
    </div>

    <PrimaryButton class="mt-4" @click="generateToken" :disabled="processing">Generate</PrimaryButton>

    <p v-if="response.message.length > 0" :class="`${response.errors === true ? 'text-red-500' : 'text-green-500'}`">{{ response.message }}</p>
</template>

<script>
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
export default {
    name: "ApiToken",
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput
    },
    props: {
        user: {
            type: Object,
        },
        apiToken: {
            type: String,
            default: '',
        }
    },
    data() {
        return {
            processing: false,
            response: {
                errors: false,
                message: ''
            }
        }
    },
    methods: {
        generateToken() {
            this.processing = true
            this.response.message = '';

            axios.post(route('profile.generate-api-token'), {user: this.user})
            .then(response => {
                this.processing = false

                this.response = response.data;
            })
        }
    }
}
</script>

<style scoped>

</style>
