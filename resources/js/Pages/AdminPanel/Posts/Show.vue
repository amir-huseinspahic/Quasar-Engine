<script setup>
    import { ref } from 'vue'
    import { Link, router, useForm, usePage } from '@inertiajs/vue3';

    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import DangerButton from '@/Components/Base/DangerButton.vue';
    import Modal from '@/Components/Base/Modal.vue';


    const props = defineProps({
        post: {
            type: Object,
            required: true
        },
        userPreferences: {
            type: [Array, Object],
            required: true
        }
    });

    console.log(props.post)

    const { getHRT } = getHumanReadableTime();

    const deletePostForm = useForm ({});
    const isDeletionModalShown = ref(false);

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('posts.index'));
    }

    function showModal() {
        isDeletionModalShown.value = true;
    }

    function closeModal() {
        isDeletionModalShown.value = false;
    }

    const deletePostSubmit = () => {
        deletePostForm.post(route('posts.destroy', { post: props.post }));
    }

</script>

<template>

    <AuthenticatedLayout :page-title="$t('Post Details')">

        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <PrimaryButton type="button" @click="back">
                {{ $t('Back') }}
            </PrimaryButton>

            <div class="space-x-2">
                <Link :href="route('posts.edit', { post: props.post })">
                    <PrimaryButton type="button">
                        {{ $t('Edit') }}
                    </PrimaryButton>
                </Link>

                <DangerButton type="button" @click="showModal">
                    {{ $t('Delete') }}
                </DangerButton>
            </div>
        </section>

        <div class="max-w-7xl mx-auto mt-4">

            <div class="my-8 flex flex-col rounded-md">
                <div class="flex flex-col p-1 lg:flex-row lg:justify-between">
                    <div class="space-x-2">
                        <span class="w-fit bg-orange-500 shadow rounded-md px-2 py-1 text-white">
                            {{ post.category.name }}
                        </span>
                        <span class="w-fit shadow rounded-md px-2 py-1" :class="post.published ? 'bg-green-400 text-green-800' : 'bg-yellow-400 text-yellow-800'">
                            {{ post.published ? $t('Public') : $t('Private') }}
                        </span>
                    </div>
                    <div class="mt-3 lg:my-auto text-gray-700">
                        <p>{{ $t('Posted by: ') }}
                            <Link :href="route('users.show', { user: post.author })" class="font-bold text-indigo-600">
                            {{ post.author.name }}
                            </Link></p>
                        <p>{{ $t('Posted at: ') }}<span class="font-bold">
                            {{
                                getHRT(post.created_at,
                                    $page.props.auth.user.settings.timezone,
                                    $page.props.auth.user.settings.date_format,
                                    $page.props.auth.user.settings.time_format)
                            }}
                        </span></p>
                    </div>
                </div>
                <div class="prose lg:prose-lg max-w-none p-2">
                    <h2>{{ post.title }}</h2>
                    <p v-html="post.forewords"></p>
                </div>
                <div class="w-11/12 mx-auto lg:w-1/2 my-6">
                    <img v-if="post.thumbnail" class="rounded-md shadow-lg object-cover w-full max-h-[600px]" :src="props.post.thumbnail" alt="">
                </div>
                <div class="prose lg:prose-lg max-w-none p-2">
                    <p v-html="post.content"></p>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-1 max-w-2xl mx-auto p-2 m-2 rounded">
                    <div class="relative" v-for="images in post.media">
                        <img :src="images.path" alt="">
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="isDeletionModalShown" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium">
                    {{ $t('Are you sure you want to delete this post?') }}
                </h2>
                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeModal">
                        {{ $t('Cancel') }}
                    </PrimaryButton>
                    <form @submit.prevent="deletePostSubmit">
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': deletePostForm.processing }"
                            :disabled="deletePostForm.processing">
                            {{ $t('Confirm') }}
                        </DangerButton>
                    </form>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
