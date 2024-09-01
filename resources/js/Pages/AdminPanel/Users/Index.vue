<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import { Link, router, useForm, usePage } from '@inertiajs/vue3'
    import {
        CreditCardIcon,
        EllipsisVerticalIcon,
        PlusCircleIcon,
        TableCellsIcon
    } from '@heroicons/vue/24/outline/index.js'
    import Pagination from '@/Components/Base/Pagination.vue'
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue'
    import { ref, watch } from 'vue'
    import { throttle } from 'lodash'

    const props = defineProps({
        users: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object
        }
    });

    let search = ref(props.filters.search);
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

    function getHRT(datetime) {
        return dayjs(datetime)
            .tz(usePage().props.auth.user.settings.timezone)
            .local(usePage().props.auth.user.settings.locale)
            .format(usePage().props.auth.user.settings.date_format +  ", " + usePage().props.auth.user.settings.time_format);
    }

    watch(search, throttle(function (value) {
        router.get(route('users.index'), { search: value }, { preserveState: true, replace: true });
    }, 500));
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Users')">

        <section class="p-3 max-w-[1920px] flex">

            <Link class="my-auto ml-auto" :href="route('users.create')">
                <PrimaryButton type="button">
                    <PlusCircleIcon class="size-5 mr-1" />
                    {{ $t('Register User') }}
                </PrimaryButton>
            </Link>

        </section>


        <div class="max-w-7xl mx-auto mt-4 p-2">

            <section class="flex">
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

                <input class="border border-gray-200 rounded-md h-10 my-auto ml-auto focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out" v-model="search" id="postSearch" type="text" :placeholder="$t('Search') + '...'">
            </section>

            <Transition
                enter-active-class="transition ease-out"
                enter-from-class="opacity-0"
            >
                <table class="hidden table-auto min-w-full mt-4 text-left divide-y divide-gray-200 rounded-lg border outline outline-1 outline-gray-300 overflow-hidden shadow lg:table">
                    <thead class="bg-gray-100 text-gray-500 rounded-2xl">
                    <tr>
                        <th class="font-normal px-3 py-2">{{ $t('Name and surname') }}</th>
                        <th class="font-normal px-3 py-2">{{ $t('E-mail') }}</th>
                        <th class="font-normal px-3 py-2">{{ $t('Privileges') }}</th>
                        <th class="font-normal px-3 py-2">{{ $t('Date of registration') }}</th>
                        <th class="font-normal px-3 py-2"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="user in props.users.data">
                            <td class="px-3 py-2">{{ user.name }}</td>
                            <td class="px-3 py-2">{{ user.email }}</td>
                            <td class="px-3 py-2"></td>
                            <td class="px-3 py-2">{{ getHRT(user.created_at) }}</td>
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
                                <h1 class="text-gray-700 text-lg font-bold">{{ user.name }} <span class="text-sm font-normal text-green-500">Admin</span></h1>
                                <p class="text-sm text-gray-500">{{ user.email }}</p>
                            </div>
                            <Link class="my-auto ml-auto" :href="route('users.show', { user: user })">
                                <EllipsisVerticalIcon class="size-8 p-1 my-auto rounded-md hover:bg-gray-200 transition-all ease-out" />
                            </Link>
                        </div>
                    </template>
                </div>
            </Transition>
            <Pagination class="my-10 flex justify-center space-x-2" :links="users.links" />
        </div>
    </AuthenticatedLayout>
</template>
