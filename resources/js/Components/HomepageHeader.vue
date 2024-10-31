<script setup>
    import { ref } from 'vue'
    import { Link, useForm } from '@inertiajs/vue3';

    import { Bars3Icon } from '@heroicons/vue/24/outline/index.js';

    const props = defineProps({
        appName: {
            required: true,
            type: String
        },
        galleryCount: {
            type: Number
        },
        postsCount: {
            type: Number
        }
    })

    const isMobileMenuOpen = ref(false);

    const changeLocale = useForm({
        locale: ''
    });

    const langList = ['en', 'bs']


    function lockScroll() {
        document.body.classList.toggle('overflow-y-hidden');
    }

    function unlockScroll() {
        document.body.classList.remove('overflow-y-hidden');
    }

    function toggleMobileMenu() {
        isMobileMenuOpen.value = !isMobileMenuOpen.value;

        if (isMobileMenuOpen.value === true) lockScroll();
        else unlockScroll();
    }

    function handleLocale(event) {
        let tempLocale = event.target.value;

        if (tempLocale) {
            changeLocale.locale = tempLocale
            changeLocale.post(route('home.handleLocale'), { preserveScroll: true, preserveState: true });
        }
    }

    function handleRouteSelection(anchor) {
        if (route().current() === 'home') return anchor;
        else return route('home') + anchor;
    }

</script>

<template>
    <header class="flex w-full bg-gradient-to-b from-slate-900 to-black text-white items-center p-5 shadow-2xl z-10">
        <div class="flex items-center space-x-1">
            <img class="h-10 w-auto lg:h-14" src="/media/app/logo.png" alt="logo">
            <h1 class="text-xl sm:text-2xl lg:text-3xl uppercase">{{ props.appName }}</h1>
        </div>

        <nav class="hidden lg:flex items-center ml-20" aria-label="desktop-menu">
            <ul class="flex space-x-4">
                <li>
                    <Link class="hover:text-indigo-600 transition-colors ease-out" :href="handleRouteSelection('#landing')" v-smooth-scroll>
                        {{ $t('Landing') }}
                    </Link>
                </li>
                <li>
                    <Link class="hover:text-indigo-600 transition-colors ease-out" :href="handleRouteSelection('#about')" v-smooth-scroll>
                        {{ $t('About') }}
                    </Link>
                </li>
                <li v-if="$page.props.app.settings.posts === true">
                    <Link class="hover:text-indigo-600 transition-colors ease-out" :href="handleRouteSelection('#news')" v-smooth-scroll>
                        {{ $t('News') }}
                    </Link>
                </li>
                <li v-if="props.galleryCount > 0 && $page.props.app.settings.gallery === true">
                    <Link class="hover:text-indigo-600 transition-colors ease-out" :href="handleRouteSelection('#gallery')" v-smooth-scroll>
                        {{ $t('Gallery') }}
                    </Link>
                </li>
                <li v-if="props.postsCount > 0 && $page.props.app.settings.posts === true">
                    <Link class="hover:text-indigo-600 transition-colors ease-out" :href="handleRouteSelection('#posts')" v-smooth-scroll>
                        {{ $t('Posts') }}
                    </Link>
                </li>
            </ul>
        </nav>

        <select class="hidden lg:flex ml-auto text-sm py-1 text-gray-800 border border-gray-300 rounded-md shadow-sm transition-colors ease-out"
                @change="handleLocale($event)">
            <template v-for="lang in langList">
                <option :value="lang"
                        :selected="$page.props.guestLocale === lang"
                        :disabled="$page.props.guestLocale === lang"
                        v-html="lang === 'bs' ? 'Bosanski' : 'English'" />
            </template>
        </select>

        <button class="ml-auto lg:hidden" type="button" @click="toggleMobileMenu">
            <Bars3Icon class="size-7" />
        </button>
    </header>

    <Transition
        enter-active-class="transition transform ease-out duration-300"
        enter-from-class="opacity-0 -translate-x-10"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-10"
    >
    <div class="absolute top-0 z-50 bg-black w-full text-5xl flex flex-col justify-center text-white bg-opacity-50 backdrop-blur" v-if="isMobileMenuOpen">
        <select class="absolute top-8 left-4 w-1/3 text-gray-800 px-6 border border-gray-300 rounded-md shadow-sm transition-colors ease-out"
                @change="handleLocale($event)">
            <template v-for="lang in langList">
                <option :value="lang"
                        :selected="$page.props.guestLocale === lang"
                        :disabled="$page.props.guestLocale === lang"
                        v-html="lang === 'bs' ? 'Bosanski' : 'English'" />
            </template>
        </select>

        <button class="text-8xl self-end px-6" type="button" @click="toggleMobileMenu">
            &times;
        </button>

            <nav class="flex flex-col min-h-screen items-center py-8" aria-label="mobile-menu">
                <Link class="w-full text-center py-6" @click="unlockScroll" :href="handleRouteSelection('#landing')" v-smooth-scroll>{{ $t('Landing') }}</Link>
                <Link class="w-full text-center py-6" @click="unlockScroll" :href="handleRouteSelection('#about')" v-smooth-scroll>{{ $t('About') }}</Link>
                <Link class="w-full text-center py-6" @click="unlockScroll" :href="handleRouteSelection('#news')" v-if="$page.props.app.settings.posts === true" v-smooth-scroll>{{ $t('News') }}</Link>
                <Link class="w-full text-center py-6" @click="unlockScroll" :href="handleRouteSelection('#gallery')" v-if="props.galleryCount > 0 && $page.props.app.settings.gallery === true" v-smooth-scroll>{{ $t('Gallery') }}</Link>
                <Link class="w-full text-center py-6" @click="unlockScroll" :href="handleRouteSelection('#posts')" v-if="props.postsCount > 0 && $page.props.app.settings.posts === true" v-smooth-scroll>{{ $t('Posts') }}</Link>
            </nav>

    </div>
    </Transition>
</template>
