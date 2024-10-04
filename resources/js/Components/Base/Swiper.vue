<script setup>
    import { register } from 'swiper/element/bundle';
    import { ref, onMounted } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';

    import { ClockIcon } from '@heroicons/vue/24/outline/index.js';

    register();

    const { dayjs, getTimeFromNow } = getHumanReadableTime();

    let dayjsLocale = usePage().props.guestLocale ?? 'en';
    dayjs.locale(dayjsLocale.toString());


    const props = defineProps({
        data: {
            type: [Array, Object]
        },
        showCategory: {
            type: Boolean,
            default: false
        }
    })

    const swiperElement = ref(null)

    const swiperParams = {
        navigation: {
            enabled: true,
        },
        scrollbar: {
            enabled: true,
            draggable: true
        },
        mousewheel: {
            enabled: true
        },
        autoplay: {
            enabled: false,
            delay: 5000,
            disableOnInteraction: true,
            pauseOnMouseEnter: true
        },
        slidesPerView: 1,
        spaceBetween: 0,
        breakpoints: {
            540: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 25,
            },
        },
        on: {
            init() {
                // ...
            },
        },
    };

    onMounted(() => {
        Object.assign(swiperElement.value, swiperParams);
        swiperElement.value.initialize();
    });

</script>

<template>
    <swiper-container init="false" ref="swiperElement">
        <swiper-slide class="h-auto pb-4"  v-for="item in props.data">
            <Link class="bg-white border flex flex-col shadow rounded-md group h-full"
                  :href="route('home.postDetails', { slug: item.slug })" preserve-scroll>
                <img class="h-48 w-full object-cover rounded-t-md"
                     :src="item.thumbnail" :alt="item.title">
                <div class="flex flex-col p-3 flex-1">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">{{ item.author.name }}</span>
                        <span class="text-sm text-gray-600" v-if="props.showCategory">{{ item.category.name }}</span>
                    </div>
                    <h1 class="font-semibold text-gray-800 mb-4 group-hover:text-indigo-500 transition-colors ease-out">
                        {{ item.title }}
                    </h1>
                    <div class="text-gray-600 mt-auto flex gap-1 text-sm">
                        <ClockIcon class="size-5" />
                        <span class="capitalize">{{ getTimeFromNow(item.created_at, $page.props.guestLocale, dayjs.tz.guess()) }}</span>
                    </div>
                </div>
            </Link>
        </swiper-slide>
    </swiper-container>
</template>
