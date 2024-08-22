<script setup>
    import { ref, watch } from 'vue';
    import { router, Link, useForm, usePage } from '@inertiajs/vue3'
    import { throttle } from 'lodash';
    import detectMobile from '@/detectMobile.js'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import Pagination from '@/Components/Base/Pagination.vue';
    import { EllipsisVerticalIcon, TableCellsIcon, CreditCardIcon, MagnifyingGlassIcon, PlusCircleIcon, CalendarIcon } from '@heroicons/vue/24/outline/index.js';

    const props = defineProps({
        posts: {
            type: Object
        },
        filters: {
            type: Object
        }
    });

    const userPreferencesForm = useForm({
        page_layout: usePage().props.auth.user.settings.page_layout,
        items_per_page: usePage().props.auth.user.settings.items_per_page
    });

    let isMobile = detectMobile();
    let search = ref(props.filters.search);
    let perPage = [ 5, 10, 25, 50, 100 ];

    function switchLayout(value) {
        if (value) {
            userPreferencesForm.page_layout = value;
            userPreferencesForm.post(route('updateUserPreferences'), {
                preserveScroll: true,
                preserveState: true,
                replace: true
            });
        }
    }

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
        router.get(route('posts.index'), { search: value }, { preserveState: true, replace: true });
    }, 500));

</script>

<template>
    <AuthenticatedLayout :page-title="$t('Posts')">

        <div class="max-w-7xl mx-auto mt-4 p-2">

            <section class="flex py-2 justify-between">
                <div class="flex flex-col space-y-2">
                    <div class="grid grid-cols-2 border border-gray-200 divide-x divide-gray-200" v-if="!isMobile">
                        <button class="p-2 transition-all ease-out"
                                :class="$page.props.auth.user.settings.page_layout === 'table' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700'"
                                @click="switchLayout('table')">
                            <TableCellsIcon class="size-7" />
                        </button>
                        <button class="p-2 transition-all ease-out"
                                :class="$page.props.auth.user.settings.page_layout === 'cards' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700'"
                                @click="switchLayout('cards')">
                            <CreditCardIcon class="size-7" />
                        </button>
                    </div>
                    <select class="px-2 py-1 rounded-md border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                            name="itemsPerPageSelect"
                            id="itemsPerPageSelect"
                            @change="setItemsPerPage($event)" v-model="$page.props.auth.user.settings.items_per_page">
                        <template v-for="item in perPage">
                            <option :selected="$page.props.auth.user.settings.items_per_page === item"
                                    :value="item"
                                    :disabled="$page.props.auth.user.settings.items_per_page === item">
                                {{ item }}
                            </option>
                        </template>
                    </select>
                </div>

                <input class="border border-gray-200 rounded-md w-1/3 h-10 my-auto focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors ease-out"
                       v-model="search"
                       id="postSearch"
                       type="text"
                       :placeholder="$t('Search') + '...'">

                <Link class="my-auto" :href="route('posts.create')">
                    <PrimaryButton type="button">
                        <PlusCircleIcon class="size-5 mr-1" />
                        {{ $t('Create Post') }}
                    </PrimaryButton>
                </Link>
            </section>



            <Transition
                enter-active-class="transition ease-out"
                enter-from-class="opacity-0"
            >
                <table class="table-auto min-w-full mt-4 text-left divide-y divide-gray-200 rounded-lg border outline outline-1 outline-gray-300 overflow-hidden shadow"
                       v-show="isMobile !== true && $page.props.auth.user.settings.page_layout === 'table'">
                    <thead class="bg-gray-100 text-gray-500 rounded-2xl">
                        <tr>
                            <th class="font-normal px-3 py-2">{{ $t('Title') }}</th>
                            <th class="font-normal px-3 py-2">{{ $t('Author') }}</th>
                            <th class="font-normal px-3 py-2">{{ $t('Category') }}</th>
                            <th class="font-normal px-3 py-2">{{ $t('Status') }}</th>
                            <th class="font-normal px-3 py-2">{{ $t('Date') }}</th>
                            <th class="font-normal px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="post in props.posts.data">
                            <td class="px-3 py-2 max-w-sm">{{ post.title }}</td>
                            <td class="px-3 py-2">{{ post.author }}</td>
                            <td class="px-3 py-2">{{ post.category }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded-xl select-none" :class="post.published ? 'bg-green-400 text-green-800' : 'bg-yellow-400 text-yellow-800'">
                                    {{ post.published ? $t('Public') : $t('Private') }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ getHRT(post.created_at) }}</td>
                            <td class="items-center align-middle p-1">
                                <Link :href="route('posts.show', { post: post })">
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
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-2"
                     v-show="isMobile === true || $page.props.auth.user.settings.page_layout === 'cards'">
                    <template class="" v-for="post in props.posts.data">
                        <Link class="bg-white border flex flex-col shadow rounded-md group" :href="route('posts.show', { post: post })">
                            <img class="h-48 w-full object-cover rounded-t-md" :src="'/media/posts/thumbnails/' + post.thumbnail" alt="">
                            <section class="flex flex-col p-3 flex-1">
                                <span class="text-sm text-gray-600">{{ post.category }}</span>
                                <h1 class="font-semibold text-gray-800 mb-2 group-hover:text-indigo-500 transition-colors ease-out">{{ post.title }}</h1>
                                <div class="text-gray-600 mt-auto flex gap-1">
                                    <CalendarIcon class="size-5" />
                                    <span>{{ getHRT(post.created_at) }}</span>
                                </div>
                                <div class="inline-flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ post.author }}</span>
                                    <span class="px-2 py-1 rounded-xl" :class="post.published ? 'bg-green-400 text-green-800' : 'bg-yellow-400 text-yellow-800'">
                                        {{ post.published ? $t('Public') : $t('Private') }}
                                    </span>
                                </div>
                            </section>
                        </Link>
                    </template>
                </div>
            </Transition>


            <Pagination class="my-10 flex justify-center space-x-2" :links="posts.links" />
        </div>

    </AuthenticatedLayout>
</template>
