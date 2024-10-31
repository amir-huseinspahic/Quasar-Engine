<script setup>
    import { router, useForm, usePage } from '@inertiajs/vue3'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import DangerButton from '@/Components/Base/DangerButton.vue'
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue'
    import InputError from '@/Components/Base/InputError.vue'
    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputField from '@/Components/Base/InputField.vue'
    import SelectField from '@/Components/Base/SelectField.vue'
    import Checkbox from '@/Components/Base/Checkbox.vue'

    import dayjs from 'dayjs';
    import dayjsUTC from 'dayjs/plugin/utc';
    import dayjsTimezone from 'dayjs/plugin/timezone';

    dayjs.extend(dayjsUTC);
    dayjs.extend(dayjsTimezone);

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

    const updateProfileForm = useForm({
        name: props.user.name,
        email: props.user.email,
        locale: props.user.settings.locale,
        timezone:  props.user.settings.timezone,
        date_format:  props.user.settings.date_format,
        time_format:  props.user.settings.time_format,
        show_private_posts:  props.user.settings.show_private_posts,
    });

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('dashboard'));
    }

    let localeOptions = [
        {
            value: 'en',
            text: 'English'
        },
        {
            value: 'bs',
            text: 'Bosanski'
        }
    ];

    let dateOptions = [
        {
            value: 'DD-MM-YYYY',
            text: dayjs.utc().format('DD-MM-YYYY') + ' (Day-Month-Year)',
            textBS: dayjs.utc().format('DD-MM-YYYY') + ' (Dan-Mjesec-Godina)'
        },
        {
            value: 'YYYY-MM-DD',
            text: dayjs.utc().format('YYYY-MM-DD') + ' (Year-Month-Day)',
            textBS: dayjs.utc().format('YYYY-MM-DD') + ' (Godina-Mjesec-Dan)'
        },
        {
            value: 'MM-DD-YYYY',
            text: dayjs.utc().format('MM-DD-YYYY') + ' (Month-Day-Year)',
            textBS: dayjs.utc().format('MM-DD-YYYY') + ' (Mjesec-Dan-Godina)'
        },
    ];

    let timeOptions = [
        {
            value: 'HH:mm:ss',
            text: '24-hour clock',
            textBS: '24-satni format'
        },
        {
            value: 'h:mm:ss A',
            text: '12-hour clock',
            textBS: '12-satni format'
        }
    ];

    const updateProfileSubmit = () => {
        updateProfileForm.post(route('profile.update'), {
            onError: () => updateProfileForm.reset(),
            onSuccess: () => updateProfileForm.reset()
        });
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
                </div>
                <form @submit.prevent="updateProfileSubmit">
                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5">
                        <div>
                            <InputLabel for="name">{{ $t('Name and surname') }}</InputLabel>
                            <InputField
                                id="name"
                                name="name"
                                type="text"
                                maxlenght="100"
                                class="mt-1 block w-full"
                                v-model="updateProfileForm.name" />
                            <InputError class="mt-2" :message="updateProfileForm.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="email">{{ $t('E-mail') }}</InputLabel>
                            <InputField
                                id="email"
                                name="email"
                                type="email"
                                maxlenght="100"
                                class="mt-1 block w-full"
                                v-model="updateProfileForm.email" />
                            <InputError class="mt-2" :message="updateProfileForm.errors.name" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <InputLabel for="locale">{{ $t('Language') }}</InputLabel>
                            <SelectField id="locale"
                                         name="locale"
                                         :options="localeOptions"
                                         v-model="updateProfileForm.locale" />
                            <InputError class="mt-2" :message="updateProfileForm.errors.locale" />
                        </div>

                        <div>
                            <InputLabel for="timezone">{{ $t('Timezone') }}</InputLabel>
                            <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                                    id="timezone"
                                    name="timezone"
                                    v-model="updateProfileForm.timezone">
                                <template v-for="timezone in props.timezone_list">
                                    <option :value="timezone">{{ timezone }}</option>
                                </template>
                            </select>
                            <InputError class="mt-2" :message="updateProfileForm.errors.locale" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:grid sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <InputLabel for="date_format">{{ $t('Date format') }}</InputLabel>
                            <SelectField id="date_format"
                                         name="date_format"
                                         :options="dateOptions"
                                         :locale="$page.props.auth.user.settings.locale"
                                         v-model="updateProfileForm.date_format" />
                            <InputError class="mt-2" :message="updateProfileForm.errors.date_format" />
                        </div>

                        <div>
                            <InputLabel for="time_format">{{ $t('Time format') }}</InputLabel>
                            <SelectField id="time_format"
                                         name="time_format"
                                         :options="timeOptions"
                                         :locale="$page.props.auth.user.settings.locale"
                                         v-model="updateProfileForm.time_format" />
                            <InputError class="mt-2" :message="updateProfileForm.errors.time_format" />
                        </div>
                    </div>
                    <div class="w-fit mt-6">
                        <div class="flex space-x-2">
                            <Checkbox id="showPrivatePosts" name="showPrivatePosts" v-model:checked="updateProfileForm.show_private_posts" />
                            <InputLabel for="showPrivatePosts">{{ $t('Show private posts on the front page') }}</InputLabel>
                        </div>
                        <InputError class="mt-2" :message="updateProfileForm.errors.show_private_posts" />
                    </div>

                    <PrimaryButton class="mt-8" type="submit">{{ $t('Submit') }}</PrimaryButton>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
