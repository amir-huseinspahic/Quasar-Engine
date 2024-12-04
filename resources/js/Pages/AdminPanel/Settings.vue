<script setup>
import { useForm, usePage } from '@inertiajs/vue3'


    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import InputLabel from '@/Components/Base/InputLabel.vue';
    import Checkbox from '@/Components/Base/Checkbox.vue';
    import InputError from '@/Components/Base/InputError.vue';

    const props = defineProps({
        appSettings: {
            required: true,
            type: Object
        }
    });

    const updateFormAI = useForm({ ai: props.appSettings.ai_moderation });
    const updateFormPost = useForm({ posts: props.appSettings.posts });
    const updateFormGallery = useForm({ gallery: props.appSettings.gallery });

    const onSettingAIChanged = () => {
        updateFormAI.post(route('settings.update.ai'), {
            preserveScroll: true,
            preserveState: true
        });
    }

    const onSettingPostChanged = () => {
        updateFormPost.post(route('settings.update.posts'), {
            preserveScroll: true,
            preserveState: true
        });
    }

    const onSettingGalleryChanged = () => {
        updateFormGallery.post(route('settings.update.gallery'), {
            preserveScroll: true,
            preserveState: true
        });
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Settings')">

        <form class="max-w-7xl mx-auto p-3 my-8 shadow-md bg-white">
            <div>
                <InputLabel>{{ $t('OpenAI Moderation for posts') }}</InputLabel>
                <label class="mt-2">
                    <Checkbox v-model:checked="updateFormAI.ai" @change="onSettingAIChanged" />
                    <span class="ms-2 text-gray-700 select-none">{{ $t('Enable if you want the AI moderation model to check posts for harmful, hateful, threatening texts.') }}</span>
                </label>
                <InputError class="mt-2" message="" />
            </div>
        </form>

        <form class="max-w-7xl mx-auto p-3 my-8 shadow-md bg-white">
            <div>
                <InputLabel>{{ $t('Posts') }}</InputLabel>
                <label class="mt-2">
                    <Checkbox v-model:checked="updateFormPost.posts" @change="onSettingPostChanged" />
                    <span class="ms-2 text-gray-700 select-none">{{ $t('Enable if you want the ability to post news and articles on your page.') }}</span>
                </label>
                <InputError class="mt-2" message="" />
            </div>
        </form>

        <form class="max-w-7xl mx-auto p-3 my-8 shadow-md bg-white">
            <div>
                <InputLabel>{{ $t('Gallery') }}</InputLabel>
                <label class="mt-2">
                    <Checkbox v-model:checked="updateFormGallery.gallery" @change="onSettingGalleryChanged" />
                    <span class="ms-2 text-gray-700 select-none">{{ $t('Enable if you want the ability to manage your page gallery.') }}</span>
                </label>
                <InputError class="mt-2" message="" />
            </div>
        </form>

    </AuthenticatedLayout>
</template>
