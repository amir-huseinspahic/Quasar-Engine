<script setup>
    import { Link, router, useForm, usePage } from '@inertiajs/vue3';
    import { ref, watch } from 'vue';
    import { throttle } from 'lodash';
    import { capitalize } from '@vue/shared';

    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Pagination from '@/Components/Base/Pagination.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';

    import { ChevronDownIcon } from '@heroicons/vue/24/solid/index.js';
    import {
        AdjustmentsHorizontalIcon,
        EllipsisVerticalIcon, FunnelIcon,
        MagnifyingGlassIcon,
        UserPlusIcon,
        TrashIcon
    } from '@heroicons/vue/24/outline/index.js';

    const props = defineProps({
        users: {
            type: Object,
            required: true,
        },
        roles: {
            type: [Object, Array],
            required: true
        },
        filters: {
            type: Object
        }
    });

    const { getHRT } = getHumanReadableTime();

    const isFiltersMenuShown = ref(localStorage.getItem('isFiltersMenuShown') === 'true')

    let searchFilter = ref(props.filters.search);
    let rolesFilter = ref(props.filters.roles);
    let fromDateFilter = ref(props.filters.fromDate);
    let toDateFilter = ref(props.filters.toDate);

    let perPage = [ 5, 10, 25, 50, 100 ];

    const userPreferencesForm = useForm({
        page_layout: usePage().props.auth.user.settings.page_layout,
        items_per_page: usePage().props.auth.user.settings.items_per_page
    });

    function setItemsPerPage(event) {
        if (event.target.value) {
            userPreferencesForm.items_per_page = event.target.value;
            userPreferencesForm.post(route('updateUserPreferences'), {
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        }
    }

    function toggleFiltersMenu() {
        isFiltersMenuShown.value = !isFiltersMenuShown.value;
        localStorage.setItem('isFiltersMenuShown', isFiltersMenuShown.value);
    }

    watch(searchFilter, throttle(function (value) {
        router.get(route('users.index'), { search: value }, { preserveState: true, replace: true });
    }, 500));

    function selectRoleFilter(event) {
        router.get(route(route().current(), route().params), { role: event.target.value }, {
            preserveState: true,
            replace: true
        })
    }

    function selectFromDateFilter(event) {
        router.get(route(route().current(), route().params), { fromDate: event.target.value }, {
            preserveState: true,
            replace: true
        })
    }

    function selectToDateFilter(event) {
        router.get(route(route().current(), route().params), { toDate: event.target.value }, {
            preserveState: true,
            replace: true
        })
    }

    function clearFilters(value) {
        router.get(route(route().current()), { search: value }, {
            replace: true,
            preserveScroll: true,
        })
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Users')">

        <section class="p-3 max-w-[1920px] flex">

            <Link class="my-auto ml-auto" :href="route('users.create')">
                <PrimaryButton type="button">
                    <UserPlusIcon class="size-5 mr-1" />
                    {{ $t('Register User') }}
                </PrimaryButton>
            </Link>

        </section>


        <div class="max-w-7xl mx-auto mt-4 p-2">

            <section class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:justify-between">

                <div class="relative inline-block text-left">
                    <div>
                        <button type="button"
                                class="border border-gray-400 inline-flex w-full justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-slate-200 transition-all ease-out"
                                id="menu-button" aria-expanded="true" aria-haspopup="true" @click="toggleFiltersMenu">
                            <AdjustmentsHorizontalIcon class="size-5" />
                            <span>{{ $t('Operations') }}</span>
                            <ChevronDownIcon class="-mr-1 size-5 text-gray-400 transform transition-all ease-out"
                                             :class="isFiltersMenuShown ? 'rotate-180' : 'rotate-0'" />
                        </button>
                    </div>

                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            class="absolute z-50 mt-2 p-2 w-72 flex flex-col origin-top-middle rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical"
                            aria-labelledby="menu-button"
                            tabindex="-1"
                            v-show="isFiltersMenuShown">

                            <div class="mt-4 w-full flex items-center justify-between">
                                <span>{{ $t('Items per page') }}</span>
                                <select class="rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                        name="itemsPerPageSelect"
                                        id="itemsPerPageSelect"
                                        @change="setItemsPerPage($event)" v-model="$page.props.auth.user.settings.items_per_page">
                                    <template v-for="item in perPage">
                                        <option :selected="$page.props.auth.user.settings.items_per_page === item"
                                                :disabled="$page.props.auth.user.settings.items_per_page === item"
                                                :value="item">
                                            {{ item }}
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <div class="mt-6 border border-gray-400 p-2 rounded-md relative">
                                <div class="absolute -top-4 left-3 p-1 flex space-x-1 items-center bg-white">
                                    <FunnelIcon class="size-5"></FunnelIcon>
                                    <span class="font-bold">{{ $t('Filters') }}</span>
                                </div>

                                <div class="mt-4 w-full flex items-center justify-between">
                                    <span>{{ $t('Per roles') }}</span>
                                    <select class="ml-2 rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                            v-model="rolesFilter"
                                            @change="selectRoleFilter">
                                        <template v-for="role in props.roles">
                                            <option :value="role">{{ capitalize(role) }}</option>
                                        </template>
                                    </select>
                                </div>

                                <div class="mt-4 w-full flex items-center justify-between">
                                    <span>{{ $t('From date') }}</span>
                                    <input class="border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                           type="date"
                                           v-model="fromDateFilter"
                                           @change="selectFromDateFilter">
                                </div>

                                <div class="mt-4 w-full flex items-center justify-between">
                                    <span>{{ $t('To date') }}</span>
                                    <input class="border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                           type="date"
                                           v-model="toDateFilter"
                                           @change="selectToDateFilter">
                                </div>

                                <button class="mt-2 flex items-center bg-indigo-500 text-white rounded-md px-2 py-1 shadow-md"
                                        type="button"
                                        @click="clearFilters(searchFilter)">
                                    <TrashIcon class="size-5 mr-1" />
                                    {{ $t('Clear filters') }}
                                </button>
                            </div>

                        </div>
                    </Transition>
                </div>

                <div class="relative">
                    <input class="border border-gray-200 rounded-md my-auto focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out pl-10 w-full"
                           v-model="searchFilter"
                           id="postSearch"
                           type="text"
                           :placeholder="$t('Search by Name, E-mail') + '...'">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <MagnifyingGlassIcon class="size-5 text-gray-500" />
                    </div>
                </div>
            </section>

            <Transition
                enter-active-class="transition ease-out"
                enter-from-class="opacity-0"
            >
                <table class="hidden table-auto min-w-full mt-4 text-left divide-y divide-gray-200 rounded-lg border outline outline-1 outline-gray-300 overflow-hidden shadow lg:table">
                    <thead class="bg-gray-100 text-gray-500 rounded-2xl">
                    <tr>
                        <th class="px-3 py-2 font-semibold">{{ $t('Name and surname') }}</th>
                        <th class="px-3 py-2 font-semibold">{{ $t('E-mail') }}</th>
                        <th class="px-3 py-2 font-semibold">{{ $t('Privileges') }}</th>
                        <th class="px-3 py-2 font-semibold">{{ $t('Date of registration') }}</th>
                        <th class="px-3 py-2 font-semibold"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="user in props.users.data">
                            <td class="px-3 py-2 flex items-center">
                                <img class="w-8 h-auto" :src="'/media/users/avatars/' + user.id + '.png'" alt="">
                                <p class="ml-2">{{ user.name }}</p>
                            </td>
                            <td class="px-3 py-2">{{ user.email }}</td>
                            <td class="px-3 py-2">{{ capitalize(user.roles) }}</td>
                            <td class="px-3 py-2">
                                {{
                                    getHRT(user.created_at,
                                        $page.props.auth.user.settings.timezone,
                                        $page.props.auth.user.settings.date_format,
                                        $page.props.auth.user.settings.time_format)
                                }}
                            </td>
                            <td class="items-center align-middle p-1">
                                <Link :href="route('users.show', { user: user })">
                                    <EllipsisVerticalIcon class="size-8 p-1 my-auto rounded-md hover:bg-gray-200 transition-all ease-out" />
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </Transition>

            <Transition
                enter-active-class="transition ease-out"
                enter-from-class="opacity-0"
            >
                <div class="lg:hidden space-y-5 mt-4">
                    <template v-for="user in props.users.data">
                        <div class="flex bg-white shadow border border-gray-200 px-2 py-1 rounded-md">
                            <div class="flex flex-col">
                                <div class="text-gray-700 text-lg font-bold flex items-center space-x-2">
                                    <img class="w-8 h-auto" :src="'/media/users/avatars/' + user.id + '.png'" alt="">
                                    <p>{{ user.name }}</p>
                                    <span class="text-sm font-normal text-green-500">{{ capitalize(user.roles) }}</span>
                                </div>
                                <p class="text-gray-500 mt-2">{{ user.email }}</p>
                            </div>
                            <Link class="my-auto ml-auto" :href="route('users.show', { user: user })">
                                <EllipsisVerticalIcon class="size-8 p-1 my-auto rounded-md hover:bg-gray-200 transition-all ease-out" />
                            </Link>
                        </div>
                    </template>
                </div>
            </Transition>
            <Pagination class="my-10" :links="users.links" />
        </div>
    </AuthenticatedLayout>
</template>
