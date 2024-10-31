<script setup>
    import { onMounted, onBeforeUnmount, ref } from 'vue'
    import { Head, Link, useForm } from '@inertiajs/vue3'

    import Swiper from '@/Components/Base/Swiper.vue';
    import { Bars3Icon, ClockIcon, ExclamationCircleIcon, ArrowUpIcon } from '@heroicons/vue/24/outline/index.js';
    import HomepageHeader from '@/Components/HomepageHeader.vue'
    import GallerySwiper from '@/Components/Base/GallerySwiper.vue'


    const props = defineProps({
        appName: {
            required: true,
            type: String
        },
        news: {
            required: false,
            type: [Object, Array]
        },
        posts: {
            required: false,
            type: [Object, Array]
        },
        gallery: {
            required: false,
            type: Object
        }
    });

    const scrollPosition = ref(0);

    onMounted(() => {
        window.addEventListener('scroll', handleScroll);
        if (sessionStorage.getItem('qa-page-y-pos')) {
            window.scrollTo(0, Number(sessionStorage.getItem('qa-page-y-pos')));
        }
    });

    onBeforeUnmount(() => {
        window.removeEventListener('scroll', handleScroll);
        sessionStorage.setItem('qa-page-y-pos', scrollPosition.value);
    });

    const handleScroll = () => {
        scrollPosition.value = window.scrollY || window.pageYOffset;
    };


</script>

<template>
    <Head title="Home" />

    <div class="flex flex-col">

        <HomepageHeader :app-name="props.appName"
                        :gallery-count="props.gallery.length"
                        :posts-count="props.posts.length"
        />

        <main>
            <div class="w-full h-[calc(100vh-96px)] flex flex-col text-white items-center select-none" id="landing">
                <div class="my-auto px-10 py-6 text-center rounded-2xl glass-effect">
                    <img class="h-32 w-auto mx-auto mb-4 lg:h-56" src="/media/app/logo.png" alt="logo">
                    <h1 class="text-3xl lg:text-6xl border-b border-indigo-500 pb-1.5 mb-4 text-nowrap uppercase">{{ props.appName }}</h1>
                    <p class="text-xl mb-2 uppercase">{{ $t('A web app built on the VILT stack') }}</p>
                    <p class="text uppercase">{{ $t('Software engineering bachelor thesis') }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-b from-black to-gray-100 px-4 py-10" id="about">
                <h1 class="text-4xl text-white ml-4 mb-6 font-light">{{ $t('About') }}</h1>

                <div class="flex items-center justify-center select-none">

                    <div class="flex flex-col text-center text-gray-800 lg:p-2">
                        <a href="https://vuejs.org/" target="_blank">
                            <img class="h-10 w-auto mx-auto lg:h-20 mb-3 translate hover:rotate-12 transition-all ease-out duration-300" src="/media/app/vue.svg" alt="vue">
                        </a>
                        <h1 class="text-white lg:text-2xl">Vue</h1>
                    </div>

                    <h1 class="text-4xl px-1 text-indigo-500 font-light lg:text-7xl lg:px-4">+</h1>

                    <div class="flex flex-col text-center text-gray-800 lg:p-2">
                        <a href="https://inertiajs.com/" target="_blank">
                            <img class="h-10 w-auto mx-auto lg:h-20 mb-3 translate hover:-rotate-12 transition-all ease-out duration-300" src="/media/app/inertia.svg" alt="inertia">
                        </a>
                        <h1 class="text-white lg:text-2xl">Inertia</h1>
                    </div>

                    <h1 class="text-4xl px-1 text-indigo-500 font-light lg:text-7xl lg:px-4">+</h1>

                    <div class="flex flex-col text-center text-gray-800 lg:p-2">
                        <a href="https://laravel.com/" target="_blank">
                            <img class="h-10 w-auto mx-auto lg:h-20 mb-3 translate hover:rotate-12 transition-all ease-out duration-300" src="/media/app/laravel.svg" alt="laravel">
                        </a>
                        <h1 class="text-white lg:text-2xl">Laravel</h1>
                    </div>

                    <h1 class="text-4xl px-1 text-indigo-500 font-light lg:text-7xl lg:px-4">+</h1>

                    <div class="flex flex-col text-center text-gray-800 lg:p-2">
                        <a href="https://tailwindcss.com/" target="_blank">
                            <img class="h-10 w-auto mx-auto lg:h-20 mb-3 translate hover:-rotate-12 transition-all ease-out duration-300" src="/media/app/tailwindcss-icon.svg" alt="tailwind">
                        </a>
                        <h1 class="text-white lg:text-2xl">Tailwind</h1>
                    </div>
                </div>

                <div class="my-6 flex justify-center mx-4">
                    <p class="text-sm mr-3 bg-gray-50 shadow-lg rounded-md text-gray-700 p-3 text-center w-56 lg:text-lg lg:mr-16">
                        {{ $t('An app made with ') }}
                        <a href="https://laravel.com/" target="_blank" class="font-bold text-indigo-600 link link-underline link-underline-black">
                            Laravel
                        </a>
                        {{ $t(' as the backend and ') }}
                        <a href="https://vuejs.org/" target="_blank" class="font-bold text-indigo-600 link link-underline link-underline-black">
                            Vue
                        </a>
                        {{ $t(' as the frontend, glued together with ') }}
                        <a href="https://inertiajs.com/" target="_blank" class="font-bold text-indigo-600 link link-underline link-underline-black">
                            Inertia.
                        </a>
                        {{ $t(' Responsive design and styling done with ') }}
                        <a href="https://tailwindcss.com/" target="_blank" class="font-bold text-indigo-600 link link-underline link-underline-black">
                            Tailwind.
                        </a>
                    </p>
                    <p class="text-sm bg-gray-50 shadow-lg rounded-md text-gray-700 p-3 text-center w-56 lg:text-lg">
                        {{ $t('Made as a baseline for common requests customers have. Managing users, writing posts, with localization and timezone support!') }}
                    </p>
                </div>
            </div>

            <div class="bg-gradient-to-b from-gray-100 to-gray-white p-4" id="news" v-if="$page.props.app.settings.posts === true">
                <h1 class="text-4xl font-light text-gray-800 ml-4 mb-6">{{ $t('News') }}</h1>

                <Swiper class="xl:w-2/3 mb-6" v-if="props.news.length > 0" :data="props.news" />
                <div class="flex bg-orange-400 rounded-md shadow-md w-full px-2 py-4 mx-auto text-white items-center justify-center md:w-1/3" v-else>
                    <ExclamationCircleIcon class="size-8 mr-2" />
                    <h1 class="md:text-lg lg:text-xl" >{{ $t('No news posted yet. Check again soon!') }}</h1>
                </div>
            </div>

            <div class="p-4" id="gallery" v-if="props.gallery.length > 0 && $page.props.app.settings.gallery === true ">
                <h1 class="text-4xl font-light text-gray-800 ml-4 mb-6">{{ $t('Gallery') }}</h1>

                <GallerySwiper :data="props.gallery" />
            </div>

            <div class="p-4 mb-10" id="posts" v-if="props.posts.length > 0 && $page.props.app.settings.posts === true">
                <h1 class="text-4xl font-light text-gray-800 ml-4 mb-6">{{ $t('Posts') }}</h1>

                <Swiper class="xl:w-2/3 mb-6" :data="props.posts" :show-category="true" />
            </div>

        </main>

        <footer class="bg-slate-900 shadow-xl flex flex-col">

            <div class="px-3 py-6 flex flex-col text-white flex-1 items-center justify-center space-y-2 text-xl border-b border-slate-800 md:text-2xl">
                <div class="pt-6 flex items-center justify-center space-x-6">
                    <a class="translate hover:text-indigo-600 hover:scale-105 transition-all ease-out" href="https://github.com/amir-huseinspahic/Quasar-Engine" target="_blank">&#127756;Github</a>
                    <a class="translate hover:text-indigo-600 hover:scale-105 transition-all ease-out" href="https://www.linkedin.com/" target="_blank">&#11088;LinkedIn</a>
                </div>

                <div class="pt-6">
                    <h1 class="translate hover:text-indigo-600 hover:scale-105 transition-all ease-out select-none">{{ $t('Made with :heart by Amir Huseinspahić', { heart: '&#10084;' }) }}</h1>
                </div>
            </div>

        </footer>
    </div>
</template>

<style>
    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #000000;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: rgb(99, 102, 241);
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px     rgba(0,0,0,0.5);

    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<style scoped>
    #landing {
        background-image: url("/media/app/landing-page.png");
        background-repeat: no-repeat;
        background-position: center;
    }

    .link-underline {
        border-bottom-width: 0;
        background-image: linear-gradient(transparent, transparent), linear-gradient(#fff, #fff);
        background-size: 0 2px;
        background-position: 0 100%;
        background-repeat: no-repeat;
        transition: background-size .5s ease-in-out;
    }

    .link-underline-black {
        background-image: linear-gradient(transparent, transparent), linear-gradient(rgb(99, 102, 241), rgb(99, 102, 241))
    }

    .link-underline:hover {
        background-size: 100% 2px;
        background-position: 0 100%
    }
</style>
