<script setup>
    import { onMounted } from 'vue'
    import { Head, usePage } from '@inertiajs/vue3'
    import HomepageHeader from '@/Components/HomepageHeader.vue';
    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';
    import Fancybox from '@/Components/Base/Fancybox.vue';

    const { dayjs, getTimeFromNow } = getHumanReadableTime();

    const props = defineProps({
        appName: {
            required: true,
            type: String
        },
        post: {
            required: true,
            type: Object
        },
        postCount: {
            type: Number
        },
        galleryCount: {
            type: Number
        }
    });
    onMounted(() => {
        window.scrollTo(0, 0);
    });
</script>

<template>
    <Head :title="props.post.title" />


    <HomepageHeader :app-name="props.appName" :posts-count="props.postCount" :gallery-count="props.galleryCount" />

    <div class="flex flex-col max-w-screen-xl mx-auto mt-3 md:mt-6">
        <span class="text-xl font-light px-2 select-none">{{ props.post.category.name }}</span>
        <h1 class="px-2 font-semibold text-2xl text-gray-800 lg:text-4xl">{{ props.post.title }}</h1>
        <div class="text-sm text-gray-800 px-2 mt-4 pb-1">
            <p>{{ $t('Writer:') }} <span class="capitalize">{{ props.post.author.name }}</span></p>
            <p>{{ $t('Posted') }} {{ getTimeFromNow(props.post.created_at, $page.props.guestLocale, dayjs.tz.guess()) }}</p>
        </div>

        <div class="w-full mx-auto md:w-2/3">
            <Fancybox>
                <a :href="props.post.thumbnail" data-fancybox :data-caption="props.post.title">
                    <img class="shadow-lg object-cover w-full max-h-[600px] md:rounded-xl" :src="props.post.thumbnail" :alt="props.post.title">
                </a>
            </Fancybox>
        </div>

        <div class="mx-auto prose max-w-none lg:w-2/3 lg:prose-lg px-3 mt-3 font-bold text-gray-800" v-if="props.post.forewords" v-html="props.post.forewords" />

        <Fancybox v-if="props.post.media.length > 0">
            <div class="w-full my-2 grid grid-cols-2 gap-1 md:w-2/3 md:mx-auto md:grid-cols-3">
                <template v-for="image in props.post.media">
                    <a :href="image.path" data-fancybox="Gallery" :data-caption="image.title">
                        <img :src="image.path" :alt="image.title">
                    </a>
                </template>
            </div>
        </Fancybox>

        <div class="mx-auto prose max-w-none text-gray-800 lg:w-2/3 lg:prose-lg px-3 mt-3 mb-12" v-html="props.post.content" />

    </div>
</template>
