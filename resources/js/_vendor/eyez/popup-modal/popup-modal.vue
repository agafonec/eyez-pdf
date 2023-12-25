<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="$emit('close')">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div :class="[
                        'inline-block align-bottom rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:p-12',
                        windowClasses,
                        maxWidth,
                    ]">
                        <button
                            v-if="!hideCloseButton"
                            type="button"
                            :class="['modal__close fixed top-4 focus:outline-none transition hover:opacity-50', `${closePosition === 'left' ? 'left-4' : 'right-4'}`, closeClasses]"
                            @click="$emit('close')"
                        >
                            <icon-close />
                        </button>

                        <slot/>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'

import IconClose from '../icons/icon-close/icon-close.vue';

export default {
    name: "PopupModal",
    components: {
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        IconClose,
    },
    props: {
        open: {type: Boolean, default: false},
        windowClasses: {type: String, default: 'bg-white'},
        maxWidth: {type: String, default: 'max-w-4xl'},
        hideCloseButton: {type: Boolean, default: false},
        closePosition: {type: String, default: 'left'},
        closeClasses: {type: String}
    },
}
</script>
