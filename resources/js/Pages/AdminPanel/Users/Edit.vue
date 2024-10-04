<script setup>
    import { Link, router, useForm, usePage } from '@inertiajs/vue3'
    import { capitalize } from '@vue/shared';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import InputField from '@/Components/Base/InputField.vue';
    import InputLabel from '@/Components/Base/InputLabel.vue';
    import InputError from '@/Components/Base/InputError.vue';
    import SelectField from '@/Components/Base/SelectField.vue'

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
            type: [Array, Object],
            required: true
        },
        roles_list: {
            type: [Array, Object],
            required: true
        }
    });

    const editUserForm = useForm({
        id: props.user.id,
        name: props.user.name,
        email: props.user.email,
        password: '',
        password_confirmation: '',
        role: '',
        locale: props.user.settings.locale,
        timezone: props.user.settings.timezone,
        date_format: props.user.settings.date_format,
        time_format: props.user.settings.time_format
    });

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

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('users.index'));
    }

    const editUserSubmit = () => {
        editUserForm.post(route('users.edit', { user: props.user }), {
            onError: () => editUserForm.reset(),
            onSuccess: () => editUserForm.reset()
        });
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Edit User')">

        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <PrimaryButton type="button" @click="back">{{ $t('Cancel') }}</PrimaryButton>
        </section>

        <form class="space-y-8 max-w-7xl mx-auto p-2 my-10" @submit.prevent="editUserSubmit">
            <div>
                <InputLabel for="name">{{ $t('Name and surname') }}</InputLabel>
                <InputField
                    id="name"
                    name="name"
                    type="text"
                    maxlenght="100"
                    class="mt-1 block w-full"
                    v-model="editUserForm.name"
                    required />
                <InputError class="mt-2" :message="editUserForm.errors.name"></InputError>
            </div>

            <div>
                <InputLabel for="email">{{ $t('E-mail') }}</InputLabel>
                <InputField
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="editUserForm.email"
                    required />
                <InputError class="mt-2" :message="editUserForm.errors.email"></InputError>
            </div>

            <div>
                <InputLabel for="new_password">{{ $t('New password') }}</InputLabel>
                <InputField
                    id="new_password"
                    name="new_password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="editUserForm.password" />
                <InputError class="mt-2" :message="editUserForm.errors.password"></InputError>
            </div>

            <div>
                <InputLabel for="new_password_confirmation">{{ $t('Confirm password') }}</InputLabel>
                <InputField
                    id="new_password_confirmation"
                    name="new_password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="editUserForm.password_confirmation" />
                <InputError class="mt-2" :message="editUserForm.errors.password_confirmation"></InputError>
            </div>

            <div>
                <InputLabel for="role">{{ $t('Role') }}</InputLabel>
                <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                        id="role">
                    <option disabled selected value style="display:none"></option>
                    <template v-for="role in props.roles_list">
                        <option :value="role">{{ capitalize(role) }}</option>
                    </template>
                </select>
                <InputError class="mt-2" :message="editUserForm.errors.role" />
            </div>

            <div>
                <InputLabel for="locale">{{ $t('Language') }}</InputLabel>
                <SelectField id="locale"
                             name="locale"
                             :options="localeOptions"
                             v-model="editUserForm.locale" />
                <InputError class="mt-2" :message="editUserForm.errors.locale" />
            </div>

            <div>
                <InputLabel for="timezone">{{ $t('Timezone') }}</InputLabel>
                <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                        id="timezone"
                        name="timezone"
                        v-model="editUserForm.timezone">
                    <template v-for="timezone in props.timezone_list">
                        <option :value="timezone">{{ timezone }}</option>
                    </template>
                </select>
                <InputError class="mt-2" :message="editUserForm.errors.locale" />
            </div>

            <div>
                <InputLabel for="date_format">{{ $t('Date format') }}</InputLabel>
                <SelectField id="date_format"
                             name="date_format"
                             :options="dateOptions"
                             :locale="$page.props.auth.user.settings.locale"
                             v-model="editUserForm.date_format" />
                <InputError class="mt-2" :message="editUserForm.errors.date_format" />
            </div>

            <div>
                <InputLabel for="time_format">{{ $t('Time format') }}</InputLabel>
                <SelectField id="time_format"
                             name="time_format"
                             :options="timeOptions"
                             :locale="$page.props.auth.user.settings.locale"
                             v-model="editUserForm.time_format" />
                <InputError class="mt-2" :message="editUserForm.errors.time_format" />
            </div>


            <section class="flex">
                <PrimaryButton type="submit" :disabled="editUserForm.processing">{{ $t('Edit') }}</PrimaryButton>
            </section>
        </form>
    </AuthenticatedLayout>
</template>
