<script setup>
    import { ref } from 'vue';
    import { useForm, Link } from "@inertiajs/vue3";

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import InputField from "@/Components/Base/InputField.vue";
    import InputLabel from "@/Components/Base/InputLabel.vue";
    import InputError from "@/Components/Base/InputError.vue";
    import TextArea from '@/Components/Base/TextArea.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import Checkbox from '@/Components/Base/Checkbox.vue';

    import { XMarkIcon, PlusIcon } from '@heroicons/vue/24/solid/index.js';
    import { ArrowUpOnSquareIcon } from '@heroicons/vue/24/outline/index.js';
    import DangerButton from '@/Components/Base/DangerButton.vue'

    const props = defineProps({
        postCategories: {
            required: true,
            type: Object
        }
    });

    const createPostForm = useForm ({
        category_id: null,
        title: '',
        forewords: '',
        thumbnail: null,
        content: '',
        published: true,
        media: null
    });

    const thumbnail = ref({
        "rawData": null,
        "preview": null,
        "togglePreview": false
    });

    const postMedia = ref([]);

    function onThumbnailSelected (event) {
        const reader = new FileReader();
        const imageData = event.target.files[0];
        thumbnail.value.rawData = event.target.files[0];

        reader.readAsDataURL(imageData);
        reader.onload = event => {
            thumbnail.value.preview = event.target.result;
            thumbnail.value.togglePreview = true;
        }

        if (imageData) {
            createPostForm.thumbnail = imageData;
        }
    }

    function onThumbnailDeselected () {
        // Clear the input field manually
        document.getElementById('thumbnail').value = null;
        thumbnail.value.rawData = null;
        thumbnail.value.preview = null;
        thumbnail.value.togglePreview = false;
    }

    function onImagesSelected (event) {
        const selectedFiles = event.target.files;
        const images = ref([]);

        for (let i = 0; i < selectedFiles.length; i++) {
            images.value.push(selectedFiles[i]);
        }

        for (let i = 0; i < images.value.length; i++) {
            const reader = new FileReader();
            reader.readAsDataURL(images.value[i]);

            reader.addEventListener("load", function() {
                postMedia.value.push({
                    rawData: event.target.files[i],
                    preview: reader.result,
                });
            }.bind(this));
        }
        console.log(postMedia.value);
    }

    function onImageDeselected (id) {
        console.log(postMedia.value[id]);
        postMedia.value.splice(id, 1);

        if (postMedia.value.length < 1) {
            document.getElementById('media').value = null;
        }
    }

    function prepareMedia () {
        console.log(postMedia.value);
        createPostForm.media = [];
        for (let i = 0; i < postMedia.value.length; i++) {
            createPostForm.media[i] = postMedia.value[i].rawData;
        }
    }

    const createPostSubmit = () => {
        if (postMedia.value.length) {
            prepareMedia();
        }

        createPostForm.post(route('posts.store'), {
            onError: () => createPostForm.reset(),
            onSuccess: () => createPostForm.reset()
        });
    }

</script>

<template>
    <AuthenticatedLayout :page-title="$t('Create Post')">
        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <Link :href="route('posts.index')">
                <PrimaryButton type="button">{{ $t('Cancel') }}</PrimaryButton>
            </Link>
        </section>

        <form class="space-y-8 max-w-7xl mx-auto p-2 my-6" @submit.prevent="createPostSubmit" enctype="multipart/form-data">
            <div>
                <InputLabel for="title">{{ $t('Title') }}<span class="text-red-500">*</span></InputLabel>
                <InputField
                    id="title"
                    name="title"
                    type="text"
                    maxlenght="100"
                    class="mt-1 block w-full"
                    v-model="createPostForm.title"
                    required
                    autofocus />
                <InputError class="mt-2" :message="createPostForm.errors.title" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 lg:mx-auto">
                <div>
                    <InputLabel for="category">{{ $t('Category') }}<span class="text-red-500">*</span></InputLabel>
                    <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                            id="category"
                            name="category"
                            v-model="createPostForm.category_id"
                            required>
                        <option class="m-1 p-1 text-lg"
                                v-for="category in postCategories"
                                :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="mt-8 lg:mt-0 lg:mx-auto lg:my-auto">
                    <InputLabel for="published">{{ $t('Visibility') }}<span class="text-red-500">*</span></InputLabel>
                    <label class="flex items-center">
                        <Checkbox id="published" name="published" v-model:checked="createPostForm.published" />
                        <span class="ms-2 select-none" v-if="createPostForm.published">{{ $t('Post will be visible on the front page.') }}</span>
                        <span class="ms-2 select-none" v-else>{{ $t('Post will not be visible on the front page.') }}</span>
                    </label>
                    <InputError class="mt-2" :message="createPostForm.errors.published" />
                </div>
            </div>

            <div>
                <InputLabel for="foreword" :value="$t('Forewords')" />
                <TextArea
                    v-model="createPostForm.forewords"
                    id="foreword"
                    name="foreword" />
                <InputError class="mt-2" :message="createPostForm.errors.forewords" />
            </div>

            <div>
                <InputLabel for="content">{{ $t('Body') }}<span class="text-red-500">*</span></InputLabel>
                <TextArea
                    v-model="createPostForm.content"
                    id="content"
                    name="content"
                    required
                />
                <InputError class="mt-2" :message="createPostForm.errors.content" />
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2">
                <div>
                    <InputLabel for="thumbnail" :value="$t('Thumbnail')" />
                    <label class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer rounded-xl hover:bg-indigo-100 transition-all"
                           for="thumbnail"
                           v-show="!thumbnail.preview"
                    >
                        <input class="hidden" id="thumbnail" name="thumbnail" type="file" accept="image/*" @input="onThumbnailSelected" />
                        <ArrowUpOnSquareIcon class="size-10 text-black my-2" />
                        <span v-if="thumbnail.togglePreview" class="mt-1 font-medium tracking-wide text-gray-700" id="filename">
                            {{ thumbnail.rawData.name }}
                        </span>
                        <span v-else class="mt-1 font-medium tracking-wide text-gray-700" id="filename">{{ $t('Thumbnail') }}</span>
                        <span class="mt-2 text-xs tracking-wide text-gray-500">Select a thumbnail</span>
                        <span class="mt-2 text-xs tracking-wide text-gray-500">Accepted formats: JPG, JPEG, PNG, SVG</span>
                    </label>

                    <div class="max-w-xl mx-auto relative mt-2" v-if="thumbnail.togglePreview">
                        <button type="button" class="absolute top-2 right-2 bg-gray-800 rounded" @click="onThumbnailDeselected">
                            <XMarkIcon class="size-7 text-white" />
                        </button>
                        <img class="rounded-xl shadow-lg" v-bind:src="thumbnail.preview" alt="">
                    </div>

                    <InputError class="mt-2" :message="createPostForm.errors.thumbnail" />
                </div>

                <div class="mt-6 xl:mt-0">
                    <InputLabel for="media" :value="$t('Media')" />
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-1 border-2 border-gray-300 border-dashed max-w-2xl p-2 m-2 mx-auto rounded bg-white">
                        <div class="relative"
                             v-for="(image, index) in postMedia" :key="index"
                        >
                            <!-- Deselect media image button -->
                            <button class="absolute top-1 right-1 bg-gray-800 rounded"
                                    type="button"
                                    @click="onImageDeselected(index)">
                                <XMarkIcon class="size-5 text-white" />
                            </button>

                            <img :src="image.preview" alt="">
                            <p class="text-center my-auto text-xs p-1">{{ image.rawData.name }}</p>
                        </div>

                        <!-- Media input -->
                        <label class="flex flex-col w-auto h-28 items-center border border-gray-200 rounded hover:bg-indigo-100 transition-all">
                            <input id="media" name="media" class="hidden" type="file" accept="image/*" multiple @change="onImagesSelected">
                            <PlusIcon class="size-10 my-auto" />
                        </label>
                    </div>
                    <InputError class="mt-2" :message="createPostForm.errors.media" />
                </div>
            </div>

            <section class="flex">
                <PrimaryButton type="submit" :disabled="createPostForm.processing">{{ $t('Submit') }}</PrimaryButton>
            </section>
        </form>

        <Transition
            enter-active-class="transition ease-out"
            enter-from-class="opacity-0"
        >
            <div v-show="createPostForm.processing" class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-900 opacity-85 flex flex-col items-center justify-center">
                <!--            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>-->
                <div class="bg-black p-3 flex flex-col items-center justify-center rounded-md">
                    <div class="animate-spin inline-block w-12 h-12 border-[3px] border-current border-t-transparent text-indigo-500 rounded-full" role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h2 class="text-center text-white text-xl font-semibold">{{ $t('Creating new post...') }}</h2>
                    <p class="w-3/4 text-center text-white">{{ $t("This may take a few minutes, please don't close this page.") }}</p>
                </div>
            </div>
        </Transition>

    </AuthenticatedLayout>
</template>
