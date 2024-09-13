<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import DangerButton from '@/Components/Base/DangerButton.vue'
    import { Link, router, useForm, usePage } from '@inertiajs/vue3'
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue'
    import InputError from '@/Components/Base/InputError.vue'
    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputField from '@/Components/Base/InputField.vue'
    import SelectField from '@/Components/Base/SelectField.vue'
    import dayjs from 'dayjs'
    import Checkbox from '@/Components/Base/Checkbox.vue'

    const props = defineProps({
        user: {
            type: Object,
            required: true
        },
        timezone_list: {
            type: Object,
            required: true
        }
    });

    function getHRT(datetime) {
        return dayjs(datetime)
            .tz(usePage().props.auth.user.settings.timezone)
            .local(usePage().props.auth.user.settings.locale)
            .format(usePage().props.auth.user.settings.date_format +  ", " + usePage().props.auth.user.settings.time_format);
    }

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('posts.index'));
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Profile')">

        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <PrimaryButton type="button" @click="back">
                {{ $t('Back') }}
            </PrimaryButton>
        </section>

        <div class="max-w-7xl mx-auto mt-4">
            <section class="bg-white p-4 shadow-md rounded-md max-w-2xl mx-auto">
                <div class="flex">
                    <img class="w-8 h-8 mr-2" :src="'/media/users/avatars/' + user.id + '.png'" alt="">
                    <h1 class="mb-8 text-2xl text-center">{{ user.name }}</h1>
                    <Link class="ml-auto" :href="route('profile.edit')">
                        <PrimaryButton type="button">{{ $t('Edit') }}</PrimaryButton>
                    </Link>
                </div>
                <div>
                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5">
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('Name and surname') }}</h1>
                            <p class="mt-1">{{ user.name }}</p>
                        </div>
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('E-mail') }}</h1>
                            <p class="mt-1">{{ user.email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('Language') }}</h1>
                            <p class="mt-1" v-if="user.settings.locale === 'bs'">Bosanski</p>
                            <p class="mt-1" v-else>English</p>
                        </div>
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('Timezone') }}</h1>
                            <p class="mt-1">{{ user.settings.timezone }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('Date format') }}</h1>
                            <p class="mt-1" v-if="user.settings.date_format === 'DD-MM-YYYY'">{{ $t('Day-Month-Year') }}</p>
                            <p class="mt-1" v-else-if="user.settings.date_format === 'YYYY-MM-DD'">{{ $t('Year-Month-Day') }}</p>
                            <p class="mt-1" v-else>{{ $t('Month-Day-Year') }}</p>
                        </div>
                        <div>
                            <h1 class="block font-medium text-sm text-gray-700">{{ $t('Time format') }}</h1>
                            <p class="mt-1" v-if="user.settings.time_format ?? 'hh:mm:ss'">{{ $t('24-hour format') }}</p>
                            <p class="mt-1" v-else>{{ $t('12-hour format') }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
