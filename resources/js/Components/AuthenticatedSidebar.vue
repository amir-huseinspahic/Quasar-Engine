<script setup>

    import { XCircleIcon, CogIcon, ChevronDoubleRightIcon, HomeIcon, NewspaperIcon, UsersIcon, Cog8ToothIcon } from '@heroicons/vue/24/solid/index.js'
    import AuthenticatedSidebarPageLink from '@/Components/AuthenticatedSidebarPageLink.vue'
    import { ref } from 'vue'
    import { usePage } from '@inertiajs/vue3'

    const props = defineProps({
        sidebarExpanded : {
            type: Boolean,
            required: true,
            default: false
        }
    });

    const emits = defineEmits(['toggleSidebarEmit']);
</script>

<template>
    <!--  MOBILE SIDEBAR  -->
    <aside class="fixed text-gray-200 top-0 right-0 bottom-0 left-0 z-50 lg:hidden"
           :class="sidebarExpanded ? 'visible' : 'invisible'">

        <div class="flex flex-col absolute top-0 bottom-0 left-0 w-3/4 py-4 bg-slate-800 drop-shadow-2xl z-10 transition-all ease-out duration-300 transform-gpu"
             :class="sidebarExpanded ? 'translate-x-0' : '-translate-x-full'">

            <!--    Sidebar Header    -->
            <div class="flex">
                <CogIcon class="size-10 min-w-10 m-1" />
                <span class="my-auto whitespace-nowrap select-none text-xl bg-gradient-to-r from-indigo-500 to-red-500 bg-clip-text text-transparent font-semibold transition-all ease-out duration-200"
                      :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">
                    QuasarEngine
                </span>
                <button class="ml-auto p-1 m-1" @click="emits('toggleSidebarEmit')">
                    <XCircleIcon class="size-8 text-indigo-500" />
                </button>
            </div>

            <!--    Spacer    -->
            <div class="flex mt-6 mb-8">
                <span class="border-b border-gray-600 w-full" />
            </div>

            <AuthenticatedSidebarPageLink :href="route('dashboard')" :active="route().current('dashboard')">
                <HomeIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto whitespace-nowrap transition-all ease-out duration-200">
                    Dashboard
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-1" v-if="$page.props.auth.permissions.includes('view users')" :href="route('users.index')" :active="route().current('users.*')">
                <UsersIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto whitespace-nowrap transition-all ease-out duration-200">
                    Users
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-1" :href="route('posts.index')" :active="route().current('posts.*')">
                <NewspaperIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto whitespace-nowrap transition-all ease-out duration-200">
                    Posts
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-auto" href="">
                <Cog8ToothIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto whitespace-nowrap transition-all ease-out duration-200">
                    Settings
                </span>
            </AuthenticatedSidebarPageLink>
        </div>
    </aside>


    <!--  DESKTOP SIDEBAR  -->
    <aside class="hidden h-full flex-col flex-none bg-slate-800 text-white p-2 z-50 lg:flex transition-all ease-out duration-300"
           :class="props.sidebarExpanded ? 'w-64' : 'w-16'">

        <!--    Sidebar Header    -->
        <div class="flex">
            <CogIcon class="size-10 min-w-10 m-1" />
            <span class="my-auto whitespace-nowrap select-none text-2xl bg-gradient-to-r from-indigo-500 to-red-500 bg-clip-text text-transparent font-semibold transition-all ease-out duration-200"
                  :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">
                QuasarEngine
            </span>
        </div>

        <!--    Sidebar toggle button    -->
        <div class="flex mt-2">
            <button class="transition-all ease-in-out duration-300 transform"
                    :class="sidebarExpanded ? 'ml-auto rotate-180 hover:-translate-x-1' : 'mx-auto rotate-0 hover:translate-x-1'"
                    type="button"
                    @click="emits('toggleSidebarEmit')">
                <ChevronDoubleRightIcon class="size-6" />
            </button>
        </div>

        <!--    Spacer    -->
        <div class="flex mt-6 mb-8">
            <span class="border-b border-gray-600 w-full" />
        </div>

        <!--    Pages    -->
        <nav class="flex flex-col h-full transition-all ease-out duration-300">

            <AuthenticatedSidebarPageLink :href="route('dashboard')" :active="route().current('dashboard')">
                <HomeIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto text-lg whitespace-nowrap transition-all ease-out duration-200"
                      :class="sidebarExpanded ? 'opacity-100 visible' : 'opacity-0 invisible'">
                    {{ $t('Dashboard') }}
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-1" v-if="$page.props.auth.permissions.includes('view users')" :href="route('users.index')" :active="route().current('users.*')">
                <UsersIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto text-lg whitespace-nowrap transition-all ease-out duration-200"
                      :class="sidebarExpanded ? 'opacity-100 visible' : 'opacity-0 invisible'">
                    {{ $t('Users') }}
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-1" :href="route('posts.index')" :active="route().current('posts.*')">
                <NewspaperIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto text-lg whitespace-nowrap transition-all ease-out duration-200"
                      :class="sidebarExpanded ? 'opacity-100 visible' : 'opacity-0 invisible'">
                    {{ $t('Posts') }}
                </span>
            </AuthenticatedSidebarPageLink>

            <AuthenticatedSidebarPageLink class="mt-auto" href="">
                <Cog8ToothIcon class="size-8 min-w-8 m-2" />
                <span class="my-auto text-lg whitespace-nowrap transition-all ease-out duration-200"
                      :class="sidebarExpanded ? 'opacity-100 visible' : 'opacity-0 invisible'">
                    {{ $t('Settings') }}
                </span>
            </AuthenticatedSidebarPageLink>
        </nav>

    </aside>
</template>
