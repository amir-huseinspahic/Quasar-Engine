<script setup>
    import { Link, router, useForm, usePage } from '@inertiajs/vue3'
    import { ref } from 'vue';
    import { capitalize } from '@vue/shared';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import DangerButton from '@/Components/Base/DangerButton.vue';
    import Modal from '@/Components/Base/Modal.vue';
    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js'

    const props = defineProps({
        user: {
            type: Object,
            required: true
        }
    });

    const { getHRT, getTimeFromNow } = getHumanReadableTime();

    const deleteUserForm = useForm ({});
    const isDeletionModalShown = ref(false);

    function showModal() {
        isDeletionModalShown.value = true;
    }

    function closeModal() {
        isDeletionModalShown.value = false;
    }

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('users.index'));
    }

    const deleteUserSubmit = () => {
        deleteUserForm.post(route('users.destroy', { user: props.user }));
    }

</script>

<template>
    <AuthenticatedLayout :page-title="props.user.name">
        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <PrimaryButton type="button" @click="back">
                {{ $t('Back') }}
            </PrimaryButton>

            <div class="space-x-2">
                <Link :href="route('users.edit', { user: props.user })">
                    <PrimaryButton type="button">
                        {{ $t('Edit') }}
                    </PrimaryButton>
                </Link>

                <DangerButton type="button" @click="showModal">
                    {{ $t('Delete') }}
                </DangerButton>
            </div>
        </section>


        <div class="max-w-7xl mx-auto mt-10">
            <div class="flex flex-col w-11/12 p-3 mx-auto text-gray-700 bg-white shadow-md rounded-lg">
                <div class="lg:flex lg:flex-row justify-evenly">
                    <div>
                        <h1 class="mt-4 mb-1 text-lg">{{ $t('Basic info') }}</h1>
                        <div class="space-y-2 ml-3">
                            <p>
                                <span class="text-gray-800 font-bold">E-mail: </span>
                                {{ user.email }}
                            </p>
                            <p>
                                <span class="text-gray-800 font-bold">{{ $t('Role: ') }}</span>
                                <span class="text-green-500">{{ capitalize(user.roles[0].name) }}</span>
                            </p>
                            <p>
                                <span class="text-gray-800 font-bold">{{ $t('Created at: ') }}</span>
                                {{
                                    getHRT(user.created_at,
                                        $page.props.auth.user.settings.timezone,
                                        $page.props.auth.user.settings.date_format,
                                        $page.props.auth.user.settings.time_format)
                                }}
                            </p>
                            <p>
                                <span class="text-gray-800 font-bold">{{ $t('Last active at: ') }}</span>
                                {{ getTimeFromNow(props.user.last_active_at, $page.props.auth.user.settings.locale, $page.props.auth.user.settings.timezone) }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h1 class="mt-6 mb-1 text-lg lg:mt-4">{{ $t('Preferences') }}</h1>
                        <div class="space-y-2 ml-3">
                            <p>
                                <span class="text-gray-800 font-bold">{{ $t('Language: ') }}</span>
                                {{ user.settings.locale === 'bs' ? 'Bosanski' : 'English' }}
                            </p>
                            <p>
                                <span class="text-gray-800 font-bold">{{ $t('Timezone: ') }}</span>
                                {{ user.settings.timezone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col mt-12 w-11/12 p-3 mx-auto text-gray-700 bg-white shadow-md rounded-lg" v-if="user.posts.length" >
                <h1 class="mx-auto text-lg font-bold mb-6">{{ $t('Posts by ') }} {{ user.name }}</h1>
                <div class="space-y-2 lg:mx-auto" v-for="(posts, index) in user.posts">
                    <Link class="text-sm" :href="route('posts.show', { post : posts })"> {{ index + 1 }}. <span class="text-indigo-600 font-bold">{{ posts.title }}</span></Link>
                </div>
            </div>
        </div>


        <Modal :show="isDeletionModalShown" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium">
                    {{ $t('Are you sure you want to delete this user?') }}
                </h2>
                <h2 class="text-lg font-medium text-red-500">
                    {{ $t('Deleting this user will also delete any posts this user has made!') }}
                </h2>
                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeModal">
                        {{ $t('Cancel') }}
                    </PrimaryButton>
                    <form @submit.prevent="deleteUserSubmit">
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': deleteUserForm.processing }"
                            :disabled="deleteUserForm.processing">
                            {{ $t('Confirm') }}
                        </DangerButton>
                    </form>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

