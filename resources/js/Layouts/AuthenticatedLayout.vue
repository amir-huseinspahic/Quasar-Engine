<script setup>
    import { ref } from 'vue';
    import { Head } from '@inertiajs/vue3';

    import AuthenticatedSidebar from '@/Components/AuthenticatedSidebar.vue';
    import Dropdown from '@/Components/Base/Dropdown.vue';
    import DropdownLink from '@/Components/Base/DropdownLink.vue';

    import { Bars3Icon, ChevronDownIcon } from '@heroicons/vue/24/solid/index.js';
    import { UserCircleIcon } from '@heroicons/vue/24/outline/index.js';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';

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

                <h1 class="text-lg font-semibold mx-4">{{ props.pageTitle }}</h1>

                <!--     Header Menu     -->
                <Dropdown class="ml-auto" />

<!--                <div class="relative inline-block text-left ml-auto">-->
<!--                    <div>-->
<!--                        <button type="button" class="inline-flex w-full bg-gray-50 justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100" id="menu-button" aria-expanded="true" aria-haspopup="true" @click="toggleDropdown">-->
<!--                            <span>{{ $page.props.auth.user.name }}</span>-->
<!--                            <ChevronDownIcon class="size-5 -mr-1 text-gray-400" />-->
<!--                        </button>-->
<!--                    </div>-->

<!--                    <Transition-->
<!--                        enter-active-class="transition ease-out duration-200"-->
<!--                        enter-from-class="opacity-0 scale-95"-->
<!--                        enter-to-class="opacity-100 scale-100"-->
<!--                        leave-active-class="transition ease-in duration-75"-->
<!--                        leave-from-class="opacity-100 scale-100"-->
<!--                        leave-to-class="opacity-0 scale-95"-->
<!--                    >-->
<!--                        <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"-->
<!--                             role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1"-->
<!--                             v-show="isDropdownExpanded">-->
<!--                            <div class="py-1" role="none">-->
<!--                                &lt;!&ndash; Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" &ndash;&gt;-->
<!--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Profile</a>-->
<!--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Sign out</a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </Transition>-->
<!--                </div>-->
            </header>

            <!--     Main Page     -->
            <main class="h-full overflow-y-scroll bg-gray-50">
                <slot></slot>
            </main>
        </div>
    </div>
</template>
