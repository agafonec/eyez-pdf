<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Opretail Information</h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your Opretail credentials.
        </p>
    </header>

    <div class="mt-6 space-y-6 max-w-xl">
        <div>
            <InputLabel for="username" value="Opretail Username" />

            <TextInput
                id="username"
                type="text"
                class="mt-1 block w-full"
                v-model="form.username"
                required
                autofocus
                autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors?.username" />
        </div>
        <div>
            <InputLabel for="opretail_password" value="Opretail Password" />

            <TextInput
                id="opretail_password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
            />

            <InputError class="mt-2" :message="form.errors?.password" />
        </div>

        <div>
            <InputLabel for="secret_key" value="Secret Key" />

            <TextInput
                id="secret_key"
                type="text"
                class="mt-1 block w-full"
                v-model="form.secret_key"
            />

            <InputError class="mt-2" :message="form.errors?.secret_key" />
        </div>
        <div>
            <InputLabel for="_akey" value="_akey" />

            <TextInput
                id="_akey"
                type="text"
                class="mt-1 block w-full"
                v-model="form._akey"
            />

            <InputError class="mt-2" :message="form.errors?._akey" />
        </div>
        <div>
            <InputLabel for="_aid" value="_aid" />

            <TextInput
                id="_aid"
                type="text"
                class="mt-1 block w-full"
                v-model="form._aid"
            />

            <InputError class="mt-2" :message="form.errors?._aid" />
        </div>
        <div>
            <InputLabel for="enterpriseId" value="enterpriseId" />

            <TextInput
                id="enterpriseId"
                type="text"
                class="mt-1 block w-full"
                v-model="form.enterpriseId"
            />

            <InputError class="mt-2" :message="form.errors?.enterpriseId" />
        </div>
        <div>
            <InputLabel for="orgId" value="orgId" />

            <TextInput
                id="orgId"
                type="text"
                class="mt-1 block w-full"
                v-model="form.orgId"
            />

            <InputError class="mt-2" :message="form.errors?.orgId" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton @click="submitForm" :disabled="form.processing">Save</PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
            </Transition>
        </div>
    </div>
</template>

<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
export default {
    name: "OpretailnformationForm",
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        Link
    },
    props: {
        opretail: {
            type: Object,
            default: {
                username: '',
                password: '',
                secret_key: '',
                _akey: '',
                _aid: '',
                enterpriseId: '',
                orgId: '',
            }
        }
    },
    data() {
        return {
            form: this.opretail
        }
    },
    methods: {
        submitForm() {
            console.log('form submitted')
            axios.post(route('profile.opretail.update'), this.form)
            .then(response => {
                let errors = response.data.errors
                console.log(errors)
                if (errors) {
                    errors = Object.keys(errors).reduce((acc, key) => {
                        acc[key] = errors[key][0];
                        return acc;
                    }, {});
                    this.form.errors = errors
                }
            })
        }
    }
}
</script>
