<script setup>
    import { ref, watch } from 'vue';
    import { router, Link, useForm, usePage } from '@inertiajs/vue3';
    import { throttle } from 'lodash';

    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';
    import detectMobile from '@/detectMobile.js';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import Pagination from '@/Components/Base/Pagination.vue';
    import Modal from '@/Components/Base/Modal.vue'
    import DangerButton from '@/Components/Base/DangerButton.vue'
    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputError from '@/Components/Base/InputError.vue'
    import InputField from '@/Components/Base/InputField.vue'
    import SecondaryButton from '@/Components/Base/SecondaryButton.vue'
    import {
        EllipsisVerticalIcon,
        TableCellsIcon,
        CreditCardIcon,
        PlusCircleIcon,
        CalendarIcon,
        FunnelIcon,
        AdjustmentsHorizontalIcon,
        TrashIcon,
        MagnifyingGlassIcon,
        PencilIcon,
        XMarkIcon,
        DocumentPlusIcon
    } from '@heroicons/vue/24/outline/index.js';
    import { ChevronDownIcon } from '@heroicons/vue/24/solid/index.js'

    const props = defineProps({
        posts: {
            type: Object,
            required: true
        },
        filters: {
            type: Object,
            required: true
        },
        post_categories: {
            type: [Object, Array],
            required: true
        }
    });

    const { getHRT } = getHumanReadableTime();

    const isFiltersMenuShown = ref(localStorage.getItem('isFiltersMenuShown') === 'true');
    const isCategoriesModalShown = ref(localStorage.getItem('isCategoriesModalShown') === 'true');

    const userPreferencesForm = useForm({
        page_layout: usePage().props.auth.user.settings.page_layout,
        items_per_page: usePage().props.auth.user.settings.items_per_page
    });

    const createCategoryForm = useForm({ name: '' });
    const deleteCategoryForm = useForm({ id: null });

    let isMobile = detectMobile();
    let perPage = [5, 10, 25, 50, 100];

    let searchFilter = ref(props.filters.search);
    let categoryFilter = ref(props.filters.category);
    let publishedFilter = ref(props.filters.published);
    let fromDateFilter = ref(props.filters.fromDate);
    let toDateFilter = ref(props.filters.toDate);


    function toggleFiltersMenu() {
        isFiltersMenuShown.value = !isFiltersMenuShown.value;
        localStorage.setItem('isFiltersMenuShown', isFiltersMenuShown.value);
    }

    function showCategoriesModal() {
        isCategoriesModalShown.value = true;
        localStorage.setItem('isCategoriesModalShown', isCategoriesModalShown.value);

    }

    function closeCategoriesModal() {
        isCategoriesModalShown.value = false;
        localStorage.setItem('isCategoriesModalShown', isCategoriesModalShown.value);

    }

    function switchLayout(value) {
        if (value) {
            userPreferencesForm.page_layout = value
            userPreferencesForm.post(route('updateUserPreferences'), {
                preserveScroll: true,
                preserveState: true,
                replace: true
            })
        }
    }

    function setItemsPerPage(event) {
        if (event.target.value) {
            userPreferencesForm.items_per_page = event.target.value
            userPreferencesForm.post(route('updateUserPreferences'), {
                preserveState: true,
                preserveScroll: true,
                replace: true
            })
        }
    }

    const createCategorySubmit = () => {
        createCategoryForm.post(route('posts.categories.store'), {
            onError: () => createCategoryForm.reset(),
            onSuccess: () => createCategoryForm.reset(),
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }

    function deleteCategory(id) {
        if (id) {
            deleteCategoryForm.id = id;
            deleteCategoryForm.post(route('posts.categories.destroy', { id: id }), {
                onError: () => deleteCategoryForm.reset(),
                onSuccess: () => deleteCategoryForm.reset(),
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        }
    }

    watch(searchFilter, throttle(function(value) {
        router.get(route(route().current(), route().params), { search: value }, { preserveState: true, replace: true })
    }, 500))

    function selectCategoryFilter(event) {
        router.get(route(route().current(), route().params), { category: event.target.value }, {
            preserveState: true,
            replace: true
        })
    }

    function selectPublishedFilter(event) {
        router.get(route(route().current(), route().params), { published: event.target.value }, {
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
        router.get(route(route().current()), { search: value })
    }

</script>

<template>
    <AuthenticatedLayout :page-title="$t('Posts')">

        <section class="p-3 max-w-[1920px] flex justify-end space-x-5">

            <SecondaryButton @click="showCategoriesModal" type="button">
                <PencilIcon class="size-5 mr-1" />
                {{ $t('Manage Categories') }}
            </SecondaryButton>

            <Link :href="route('posts.create')">
                <PrimaryButton type="button">
                    <DocumentPlusIcon class="size-5 mr-1" />
                    {{$t('Create Post')}}
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

                            <div>
                                <div class="grid grid-cols-2 border border-gray-200 divide-x divide-gray-200"
                                     v-if="!isMobile">
                                    <button class="p-2 transition-all ease-out flex items-center"
                                            :class="$page.props.auth.user.settings.page_layout === 'table' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700'"
                                            @click="switchLayout('table')">
                                        <TableCellsIcon class="size-7" />
                                        <span class="ml-2 text-sm">{{ $t('Table layout') }}</span>
                                    </button>
                                    <button class="p-2 transition-all ease-out flex items-center"
                                            :class="$page.props.auth.user.settings.page_layout === 'cards' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700'"
                                            @click="switchLayout('cards')">
                                        <CreditCardIcon class="size-7" />
                                        <span class="ml-2 text-sm">{{ $t('Card layout') }}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 w-full flex items-center justify-between">
                                <span>{{ $t('Items per page') }}</span>
                                <select
                                    class="ml-2 rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                    @change="setItemsPerPage($event)">
                                    <template v-for="item in perPage">
                                        <option :value="item"
                                                :disabled="item === $page.props.auth.user.settings.items_per_page"
                                                :selected="item === $page.props.auth.user.settings.items_per_page">
                                            {{item}}
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
                                    <span>{{ $t('Per category') }}</span>
                                    <select class="ml-2 rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                            v-model="categoryFilter"
                                            @change="selectCategoryFilter">
                                        <template v-for="categories in props.post_categories">
                                            <option :value="categories.id">{{categories.name}}</option>
                                        </template>
                                    </select>
                                </div>

                                <div class="mt-4 w-full flex items-center justify-between">
                                    <span>{{ $t('Per status') }}</span>
                                    <select class="ml-2 rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                                            v-model="publishedFilter"
                                            @change="selectPublishedFilter">
                                        <option value="false">Private</option>
                                        <option value="1">Public</option>
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
                           :placeholder="$t('Search by Title, Author') + '...'">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <MagnifyingGlassIcon class="size-5 text-gray-500" />
                    </div>
                </div>
            </section>


            <Transition
                enter-active-class="transition ease-out"
                enter-from-class="opacity-0"
            >
                <table
                    class="table-auto min-w-full mt-4 text-left divide-y divide-gray-200 rounded-lg border outline outline-1 outline-gray-300 overflow-hidden shadow"
                    v-show="isMobile !== true && $page.props.auth.user.settings.page_layout === 'table'">
                    <thead class="bg-gray-100 text-gray-500 rounded-2xl">
                    <tr>
                        <th class="px-3 py-2 font-semibold">{{$t('Title')}}</th>
                        <th class="px-3 py-2 font-semibold">{{$t('Author')}}</th>
                        <th class="px-3 py-2 font-semibold">{{$t('Category')}}</th>
                        <th class="px-3 py-2 font-semibold">{{$t('Status')}}</th>
                        <th class="px-3 py-2 font-semibold">{{$t('Date')}}</th>
                        <th class="px-3 py-2 font-semibold"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                    <tr v-for="post in props.posts.data">
                        <td class="px-3 py-2 max-w-sm">{{ post.title }}</td>
                        <td class="px-3 py-2">{{ post.author}}</td>
                        <td class="px-3 py-2">{{ post.category }}</td>
                        <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded-xl select-none"
                                      :class="post.published ? 'bg-green-400 text-green-800' : 'bg-yellow-400 text-yellow-800'">
                                    {{ post.published ? $t('Public') : $t('Private') }}
                                </span>
                        </td>
                        <td class="px-3 py-2">
                            {{
                                getHRT(post.created_at,
                                    $page.props.auth.user.settings.timezone,
                                    $page.props.auth.user.settings.date_format,
                                    $page.props.auth.user.settings.time_format)
                            }}
                        </td>
                        <td class="items-center align-middle p-1">
                            <Link :href="route('posts.show', { post: post })">
                                <EllipsisVerticalIcon
                                    class="size-8 p-1 my-auto rounded-md hover:bg-gray-200 transition-all ease-out" />
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
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-2"
                     v-show="isMobile === true || $page.props.auth.user.settings.page_layout === 'cards'">
                    <template class="" v-for="post in props.posts.data">
                        <Link class="bg-white border flex flex-col shadow rounded-md group"
                              :href="route('posts.show', { post: post })">
                            <img class="h-48 w-full object-cover rounded-t-md"
                                 :src="'/media/posts/thumbnails/' + post.thumbnail" alt="">
                            <section class="flex flex-col p-3 flex-1">
                                <span class="text-sm text-gray-600">{{post.category}}</span>
                                <h1 class="font-semibold text-gray-800 mb-2 group-hover:text-indigo-500 transition-colors ease-out">
                                    {{ post.title }}</h1>
                                <div class="text-gray-600 mt-auto flex gap-1">
                                    <CalendarIcon class="size-5" />
                                    <span>{{
                                            getHRT(post.created_at,
                                                $page.props.auth.user.settings.timezone,
                                                $page.props.auth.user.settings.date_format,
                                                $page.props.auth.user.settings.time_format)
                                        }}
                                    </span>
                                </div>
                                <div class="inline-flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ post.author }}</span>
                                    <span class="px-2 py-1 rounded-xl"
                                          :class="post.published ? 'bg-green-400 text-green-800' : 'bg-yellow-400 text-yellow-800'">
                                        {{ post.published ? $t('Public') : $t('Private') }}
                                    </span>
                                </div>
                            </section>
                        </Link>
                    </template>
                </div>
            </Transition>

            <Pagination class="my-10" :links="props.posts.links"  />
        </div>

        <Modal :show="isCategoriesModalShown" @close="closeCategoriesModal">
            <div class="p-3 text-gray-800">
                <form class="mt-4 flex border border-gray-300 px-3 py-6 rounded-md shadow relative" @submit.prevent="createCategorySubmit">
                    <div class="absolute -top-3 left-3 capitalize bg-white font-bold flex items-center">
                        <PlusCircleIcon class="size-5" />
                        <span class="ml-1">{{ $t('Add new category') }}</span>
                    </div>
                    <div class="mt-2">
                        <InputLabel>{{ $t('Category name') }}</InputLabel>
                        <InputField class="mt-1 block w-full"
                                    id="createNewCategory"
                                    name="createNewCategory"
                                    type="text"
                                    v-model="createCategoryForm.name"
                                    :placeholder="$t('Ex. News, Announcements...')"
                        />
                        <InputError class="mt-2" :message="createCategoryForm.errors.name" />
                    </div>
                    <div class="mt-auto ml-4">
                        <PrimaryButton type="submit">{{ $t('Submit') }}</PrimaryButton>
                    </div>
                </form>

                <section class="grid grid-cols-2 gap-2 mt-8 border border-gray-300 px-3 py-6 rounded-md shadow relative md:grid-cols-4">
                    <div class="absolute -top-3 left-3 capitalize bg-white font-bold">{{ $t('All categories') }}</div>
                    <template v-for="categories in props.post_categories">
                        <div class="text-center px-3 py-2 bg-gray-100 rounded shadow relative">
                            <button class="absolute -top-1 -right-1 bg-red-600 rounded-md" @click="deleteCategory(categories.id)">
                                <XMarkIcon class="size-5 text-white" />
                            </button>
                            {{ categories.name }}
                        </div>
                    </template>
                </section>

                <DangerButton class="mt-4" type="button" @click="closeCategoriesModal">{{ $t('Cancel') }}</DangerButton>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
