<script setup>
    import { ref } from 'vue'
    import { Link, useForm } from '@inertiajs/vue3'

    import PrimaryButton from '@/Components/Base/PrimaryButton.vue'
    import InputError from '@/Components/Base/InputError.vue'
    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputField from '@/Components/Base/InputField.vue'
    import TextArea from '@/Components/Base/TextArea.vue'

    import { ArrowUpOnSquareIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline/index.js'
    import { PlusIcon, XMarkIcon } from '@heroicons/vue/24/solid/index.js'
    import Checkbox from '@/Components/Base/Checkbox.vue'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

    const props = defineProps({
        post: {
            required: true,
            type: Object
        },
        postCategories: {
            required: true,
            type: Object
        },
        users: {
            required: true,
            type: Object
        }
    });

    let thumbnailPath = '/media/posts/thumbnails/';

    const thumbnail = ref({
        "rawData": null,
        "preview": null,
        "togglePreview": false
    });

    const postMedia = ref([]);

    const editPostForm = useForm({
        user_id: props.post.author.id,
        category_id: props.post.category.id,
        title: props.post.title,
        forewords: props.post.forewords,
        thumbnail: props.post.thumbnail,
        content: props.post.content,
        published: props.post.published,
        media: props.post.media,
        new_thumbnail: null,
        new_media: null,
        media_marked_for_removal: null
    });

    const deleteMediaFrom = useForm({
        media_id: null
    });

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
            editPostForm.new_thumbnail = imageData;
        }
    }

    function onThumbnailDeselected () {
        // Clear the input field manually
        document.getElementById('new_thumbnail').value = null;
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
    }

    function onImageDeselected (id) {
        postMedia.value.splice(id, 1);

        if (postMedia.value.length < 1) {
            document.getElementById('new_media').value = null;
        }
    }

    function prepareMedia () {
        editPostForm.new_media = [];
        for (let i = 0; i < postMedia.value.length; i++) {
            editPostForm.new_media[i] = postMedia.value[i].rawData;
        }
    }

    const editPostSubmit = () => {
        if (postMedia.value.length) {
            prepareMedia();
        }
        editPostForm.post(route('posts.update', { post: props.post }));
    }

    const removeMedia = (id) => {
        if (id) {
            deleteMediaFrom.media_id = id;
            deleteMediaFrom.post(route('posts.media.destroy', { post: props.post, id: id }), {
                preserveScroll: true,
                preserveState: true,
                replace: true
            });
        }
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Edit Post')">

        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <Link :href="route('posts.show', { post: post })">
                <PrimaryButton type="button">{{ $t('Cancel') }}</PrimaryButton>
            </Link>
        </section>

        <form class="space-y-8 max-w-7xl mx-auto p-2 my-6" @submit.prevent="editPostSubmit">

            <div>
                <InputLabel for="title">{{ $t('Title') }}<span class="text-red-500">*</span></InputLabel>
                <InputField
                    id="title"
                    name="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="editPostForm.title"
                    required
                    autofocus />
                <InputError class="mt-2" :message="editPostForm.errors.title" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 lg:mx-auto gap-2">
                <div>
                    <InputLabel for="author">{{ $t('Author') }}<span class="text-red-500">*</span></InputLabel>
                    <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                            id="author"
                            name="author"
                            v-model="editPostForm.user_id"
                            required>
                        <option :value="post.author.id">
                            {{ post.author.name }}
                        </option>
                        <template v-for="user in props.users">
                            <option :value="user.id" v-if="post.author.id !== user.id">
                                {{ user.name }}
                            </option>
                        </template>
                    </select>
                    <InputError class="mt-2" :message="editPostForm.errors.user_id" />
                </div>

                <div class="mt-8 lg:mt-0">
                    <InputLabel for="category">{{ $t('Category') }}<span class="text-red-500">*</span></InputLabel>
                    <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                            id="category"
                            name="category"
                            v-model="editPostForm.category_id"
                            required>
                        <option :value="post.category.id">
                            {{ post.category.name }}
                        </option>
                        <template v-for="category in postCategories">
                            <option :value="category.id" v-if="category.id !== post.category.id">
                                {{ category.name }}
                            </option>
                        </template>
                    </select>
                    <InputError class="mt-2" :message="editPostForm.errors.category_id" />
                </div>
            </div>

            <div class="w-fit">
                <InputLabel for="published">{{ $t('Visibility') }}<span class="text-red-500">*</span></InputLabel>
                <label class="flex items-center">
                    <Checkbox id="published" name="published" v-model:checked="editPostForm.published" />
                    <span class="ms-2 select-none" v-if="editPostForm.published">{{ $t('Post will be visible on the front page.') }}</span>
                    <span class="ms-2 select-none" v-else>{{ $t('Post will not be visible on the front page.') }}</span>
                </label>
                <InputError class="mt-2" :message="editPostForm.errors.published" />
            </div>

            <div>
                <InputLabel for="foreword" :value="$t('Forewords')" />
                <TextArea
                    v-model="editPostForm.forewords"
                    id="foreword"
                    name="foreword" />
                <InputError class="mt-2" :message="editPostForm.errors.forewords" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <div class="">
                    <InputLabel for="thumbnail" :value="$t('Current thumbnail')" />
                    <img class="rounded-xl shadow-lg object-cover" :src="thumbnailPath + post.thumbnail" alt="">
                </div>

                <div>
                    <InputLabel for="thumbnail" :value="$t('New thumbnail')" />
                    <label class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-4 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer rounded-xl hover:bg-indigo-100 transition-all"
                           for="new_thumbnail"
                           v-show="!thumbnail.preview">
                        <input class="hidden" id="new_thumbnail" name="new_thumbnail" type="file" accept="image/*" @input="onThumbnailSelected" />
                        <ArrowUpOnSquareIcon class="size-10 text-black my-2" />
                        <span v-if="thumbnail.togglePreview" class="mt-1 font-medium tracking-wide text-gray-700" id="filename">
                        {{ thumbnail.rawData.name }}
                    </span>
                        <span v-else class="mt-1 font-medium tracking-wide text-gray-700" id="filename">New thumbnail</span>
                        <span class="mt-2 text-xs tracking-wide text-gray-500">Click here to change the thumbnail</span>
                        <span class="mt-2 text-xs tracking-wide text-gray-500">Accepted formats: JPG, JPEG, PNG, SVG</span>
                    </label>
                    <InputError class="mt-2" :message="editPostForm.errors.thumbnail" />

                    <div class="relative" v-if="thumbnail.togglePreview">
                        <button type="button" class="absolute top-2 right-2 bg-gray-800 rounded" @click="onThumbnailDeselected">
                            <XMarkIcon class="size-7 text-white" />
                        </button>
                        <img class="rounded-xl shadow-lg" v-bind:src="thumbnail.preview" alt="">
                    </div>
                </div>
            </div>
            <div>
                <InputLabel for="content">{{ $t('Body') }}<span class="text-red-500">*</span></InputLabel>
                <TextArea
                    v-model="editPostForm.content"
                    id="content"
                    name="content"
                    required
                />
                <InputError class="mt-2" :message="editPostForm.errors.content" />
            </div>

            <div>
                <InputLabel for="media" :value="$t('Current media')" />
                <div v-if="!post.media.length" class="p-2 w-fit flex space-x-2 text-white bg-orange-500 rounded-md shadow-md">
                    <ExclamationCircleIcon class="size-6 text-white" />
                    <p>{{ $t('There is no media in this post!') }}</p>
                </div>
                <div v-else class="grid grid-cols-2 lg:grid-cols-3 gap-1 max-w-2xl mx-auto p-2 m-2 rounded">
                    <div class="relative" v-for="images in post.media">
                        <button class="absolute top-1 right-1 bg-gray-800 rounded"
                                type="button"
                                @click="removeMedia(images.id)">
                            <XMarkIcon class="size-5 text-white" />
                        </button>
                        <img :src="'/media/posts/media/' + images.name" alt="">
                    </div>
                </div>
                <InputError class="mt-2" :message="editPostForm.errors.new_media" />
            </div>

            <div>
                <InputLabel for="media" :value="$t('New media')" />
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-1 border-2 border-gray-300 border-dashed max-w-2xl p-2 m-2 mx-auto rounded">
                    <div class="relative"
                         v-for="(image, index) in postMedia" :key="index">
                        <button class="absolute top-1 right-1 bg-gray-800 rounded"
                                type="button"
                                @click="onImageDeselected(index)">
                            <XMarkIcon class="size-5 text-white" />
                        </button>
                        <img :src="image.preview" alt="">
                        <p class="text-center my-auto text-xs p-1">{{ image.rawData.name }}</p>
                    </div>
                    <label class="flex flex-col w-auto h-28 items-center border border-gray-200 rounded hover:bg-indigo-100 transition-all">
                        <input id="new_media" name="new_media" class="hidden" type="file" accept="image/*" multiple @change="onImagesSelected">
                        <PlusIcon class="size-10 my-auto"></PlusIcon>
                    </label>
                </div>
                <InputError class="mt-2" :message="editPostForm.errors.media" />
            </div>

            <PrimaryButton type="submit" :disabled="editPostForm.processing">{{ $t('Submit') }}</PrimaryButton>
        </form>


        <Transition
            enter-active-class="transition ease-out"
            enter-from-class="opacity-0"
        >
            <div v-show="editPostForm.processing" class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-900 opacity-85 flex flex-col items-center justify-center">
                <!--            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>-->
                <div class="bg-black p-3 flex flex-col items-center justify-center rounded-md">
                    <div class="animate-spin inline-block w-12 h-12 border-[3px] border-current border-t-transparent text-indigo-500 rounded-full" role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h2 class="text-center text-white text-xl font-semibold">{{ $t('Updating post...') }}</h2>
                    <p class="w-3/4 text-center text-white">{{ $t("This may take a few minutes, please don't close this page.") }}</p>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>
