<script setup>
    import { BellAlertIcon, BellSlashIcon } from '@heroicons/vue/24/outline'
    import { useToasts } from '@/Composables/ToastNotifications'

    const { toasts, toastAgain, reToasted } = useToasts()
</script>

<template>
    <div>
        <button
            v-if="toasts"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-800 hover:text-white"
            :class="{ 'animate-pulse': !reToasted }"
            @click="toastAgain()"
        >
            <BellSlashIcon class="size-5" v-if="reToasted" />
            <BellAlertIcon class="size-5" v-else />
        </button>
    </div>
</template>
