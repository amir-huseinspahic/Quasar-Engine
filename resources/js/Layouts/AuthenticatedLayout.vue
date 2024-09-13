<script setup>
    import { ref } from 'vue';
    import { Head } from '@inertiajs/vue3';

    import AuthenticatedSidebar from '@/Components/AuthenticatedSidebar.vue';
    import Dropdown from '@/Components/Base/Dropdown.vue';

    import { Bars3Icon, ChevronDownIcon } from '@heroicons/vue/24/solid/index.js';
    import ToastNotifications from '@/Components/Base/ToastNotifications.vue'

    const props = defineProps({
        pageTitle: {
            type: [String, Object],
            required: true,
            default: 'Page'
        }
    });

    const isSidebarExpanded = ref(false);

    function toggleSidebar() {
        isSidebarExpanded.value = !isSidebarExpanded.value;
    }

</script>

<template>
    <Head :title="props.pageTitle" />

    <div class="flex w-screen h-screen relative">
        <AuthenticatedSidebar :sidebar-expanded="isSidebarExpanded" @toggle-sidebar-emit="toggleSidebar" />

        <div class="flex flex-col flex-1">
            <!--    Page Header    -->
            <header class="bg-gray-50 drop-shadow px-3 py-2 flex text-gray-700 items-center">

                <button class="block my-auto mr-2 lg:hidden" type="button" @click="toggleSidebar">
                    <Bars3Icon class="size-6 text-gray-700" />
                </button>

                <h1 class="text-lg font-semibold mx-4 mr-2">{{ props.pageTitle }}</h1>

                <ToastNotifications />

                <!--     Header Menu     -->
                <Dropdown class="ml-auto" />

            </header>

            <!--     Main Page     -->
            <main class="h-full overflow-y-scroll bg-gray-50">
                <slot></slot>
            </main>
        </div>
    </div>
</template>
