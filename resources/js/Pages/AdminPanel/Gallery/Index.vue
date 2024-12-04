<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Modal from '@/Components/Base/Modal.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import InputError from '@/Components/Base/InputError.vue'
    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputField from '@/Components/Base/InputField.vue'

    import { XMarkIcon } from '@heroicons/vue/24/solid/index.js'
    import { ArrowUpOnSquareIcon, PencilSquareIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline/index.js'
    import DangerButton from '@/Components/Base/DangerButton.vue'
    import Fancybox from '@/Components/Base/Fancybox.vue'



    const props = defineProps({
        images: {
            required: true,
            type: Object
        }
    });

    const addImageToGallery = useForm({
        image: null,
        description: ''
    });

    const removeImageFromGallery = useForm({});

    const isCreateModalShown = ref(false);
    const isDeleteModalShown = ref(false);

    const idToDelete = ref(null);

    const thumbnail = ref({
        "rawData": null,
        "preview": null,
        "togglePreview": false
    });

    function showCreateModal() {
        isCreateModalShown.value = true;
    }

    function closeCreateModal() {
        isCreateModalShown.value = false;
    }

    function showDeleteModal(id) {
        isDeleteModalShown.value = true;
        idToDelete.value = id;
    }

    function closeDeleteModal() {
        isDeleteModalShown.value = false;
        idToDelete.value = null;
    }

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
            addImageToGallery.image = imageData;
        }
    }

    function onThumbnailDeselected () {
        // Clear the input field manually
        document.getElementById('thumbnail').value = null;
        thumbnail.value.rawData = null;
        thumbnail.value.preview = null;
        thumbnail.value.togglePreview = false;
    }

    const submitImage = () => {
        addImageToGallery.post(route('gallery.store'), {
            preserveScroll: true,
            preserveState: false,
            onError: () => addImageToGallery.reset(),
            onSuccess: () => addImageToGallery.reset()
        })
    }

    const deleteImage = (id) => {
        removeImageFromGallery.post(route('gallery.destroy', {id: id}), {
            preserveScroll: true,
            preserveState: false,
            onError: () => removeImageFromGallery.reset(),
            onSuccess: () => removeImageFromGallery.reset()
        })
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Gallery')">

        <div class="p-3 max-w-[1920px] flex justify-end space-x-5">

            <PrimaryButton @click="showCreateModal">{{ $t('Add Image') }}</PrimaryButton>

        </div>

        <div class="max-w-7xl mx-auto mt-4 p-2 flex flex-col space-y-6" v-if="props.images.length < 1">
            <div class="shadow-md rounded-md p-2 flex items-center justify-center text-center text-white bg-orange-400 space-x-2 select-none">
                <ExclamationCircleIcon class="size-7" />
                <p>{{ $t('There is currently no images in the gallery.') }}</p>
            </div>
        </div>

        <Fancybox>
            <div class="max-w-7xl mx-auto mt-4 p-2 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                <template v-for="item in props.images">
                    <div class="px-2 py-1 relative">
                        <a :href="item.img_path" data-fancybox="Gallery" :data-caption="item.description">
                            <img class="rounded-lg shadow-lg" :src="item.img_path" :alt="item.description">
                        </a>
                        <div class="flex justify-between mt-1 border-b border-gray-200 p-1">
                            <p class="text-gray-500 text-xs mt-1 p-1 sm:text-base ml-2">{{ item.description }}</p>
                            <div class="flex my-auto p-1">
                                <button class="bg-red-500 text-white rounded-md shadow-lg" @click="showDeleteModal(item.id)">
                                    <XMarkIcon class="size-7 p-1" />
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </Fancybox>

        <Modal :show="isCreateModalShown" @close="closeCreateModal">
            <div class="p-3 text-gray-800">
                <form @submit.prevent="submitImage" enctype="multipart/form-data">

                    <div>
                        <InputLabel for="thumbnail">{{ $t('Image') }}<span class="text-red-500">*</span></InputLabel>
                        <label class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer rounded-xl hover:bg-indigo-100 transition-all"
                               for="thumbnail"
                               v-show="!thumbnail.preview"
                        >
                            <input class="hidden" id="thumbnail" name="thumbnail" type="file" accept="image/*" @input="onThumbnailSelected" required />
                            <ArrowUpOnSquareIcon class="size-10 text-black my-2" />
                            <span v-if="thumbnail.togglePreview" class="mt-1 font-medium tracking-wide text-gray-700" id="filename">
                            {{ thumbnail.rawData.name }}
                        </span>
                            <span class="mt-2 text-xs tracking-wide text-gray-500">{{ $t('Select a picture') }}</span>
                            <span class="mt-2 text-xs tracking-wide text-gray-500">{{ $t('Accepted formats:') }} JPG, JPEG, PNG, SVG</span>
                        </label>

                        <div class="max-w-xl mx-auto relative mt-2" v-if="thumbnail.togglePreview">
                            <button type="button" class="absolute top-2 right-2 bg-gray-800 rounded" @click="onThumbnailDeselected">
                                <XMarkIcon class="size-7 text-white" />
                            </button>
                            <img class="rounded-xl shadow-lg" v-bind:src="thumbnail.preview" alt="">
                        </div>

                        <InputError class="mt-2" :message="addImageToGallery.errors.image" />
                    </div>

                    <div class="mt-6">
                        <InputLabel>{{ $t('Description (optional)') }}</InputLabel>
                        <InputField class="mt-1 block w-full" v-model="addImageToGallery.description" />
                        <InputError class="mt-1" :message="addImageToGallery.errors.description" />
                    </div>

                    <div class="flex space-x-2 mt-6">
                        <PrimaryButton type="submit" :disabled="addImageToGallery.processing">
                            {{ $t('Add Image') }}
                        </PrimaryButton>
                        <DangerButton type="button" @click="closeCreateModal" :disabled="addImageToGallery.processing">
                            {{ $t('Cancel') }}
                        </DangerButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="isDeleteModalShown" @close="closeDeleteModal">
            <div class="p-3 text-gray-800">
                <form @submit.prevent="deleteImage(idToDelete)">

                    <h1 class="text-xl text-red-600">{{ $t('Are you sure you want to delete this image?') }}</h1>

                    <div class="flex space-x-2 mt-6">
                        <DangerButton type="submit">{{ $t("Yes, I'm sure") }}</DangerButton>
                        <PrimaryButton type="button" @click="closeDeleteModal">{{ $t('Cancel') }}</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
