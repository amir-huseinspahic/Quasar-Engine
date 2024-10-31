<script setup>
    import { register } from 'swiper/element/bundle';
    import { ref, onMounted } from 'vue';
    import Fancybox from '@/Components/Base/Fancybox.vue'
    register();

    const props = defineProps({
        data: {
            type: [Array, Object]
        },
    })

    const swiperGalleryElement = ref(null)

    const swiperGalleryParams = {
        navigation: {
            enabled: true,
        },
        scrollbar: {
            enabled: true,
            draggable: true
        },
        autoplay: {
            enabled: true,
            delay: 4000,
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
        },
        on: {
            init() {
                // ...s
            },
        },
    };

    onMounted(() => {
        Object.assign(swiperGalleryElement.value, swiperGalleryParams);
        swiperGalleryElement.value.initialize();
    });
</script>

<template>
    <Fancybox>
        <swiper-container class="max-w-screen-xl" init="false" ref="swiperGalleryElement">
                <swiper-slide v-for="item in props.data">

                        <a class="h-auto" data-fancybox="gallery" :data-caption="item.description" :href="item.img_path">
                            <img class="h-64 w-full object-cover rounded-t-md lg:h-full pb-6" :src="item.img_path" :alt="item.description">
                        </a>
                </swiper-slide>
        </swiper-container>
    </Fancybox>
</template>
