<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue';
    import InputField from '@/Components/Base/InputField.vue';
    import InputLabel from '@/Components/Base/InputLabel.vue';
    import InputError from '@/Components/Base/InputError.vue';
    import SelectField from '@/Components/Base/SelectField.vue'
    import dayjs from 'dayjs'


    const props = defineProps({
        timezone_list: {
            type: [Object, Array],
            required: true
        }
    });

    const createUserForm = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        locale: '',
        timezone: '',
        date_format: '',
        time_format: ''
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
            value: 'hh:mm:ss',
            text: '12-hour clock',
            textBS: '12-satni format'
        }
    ];

    function back() {
        let urlPrev = usePage().props.urlPrev;

        if (urlPrev !== 'empty') window.history.back();
        else router.visit(route('users.index'));
    }

    const createUserSubmit = () => {
        createUserForm.post(route('users.store'), {
            onError: () => createUserForm.reset(),
            onSuccess: () => createUserForm.reset()
        });
    }
</script>

<template>
    <AuthenticatedLayout :page-title="$t('Register User')">

        <section class="p-2 max-w-[1920px] flex py-3 justify-between">
            <PrimaryButton type="button" @click="back">{{ $t('Cancel') }}</PrimaryButton>
        </section>

        <form class="space-y-8 max-w-7xl mx-auto p-2 my-10" @submit.prevent="createUserSubmit">
            <div>
                <InputLabel for="name">{{ $t('Name and surname') }}<span class="text-red-500">*</span></InputLabel>
                <InputField
                    id="name"
                    name="name"
                    type="text"
                    maxlength="50"
                    class="mt-1 block w-full"
                    v-model="createUserForm.name"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="createUserForm.errors.name" />
            </div>

            <div>
                <InputLabel for="email">{{ $t('E-mail') }}<span class="text-red-500">*</span></InputLabel>
                <InputField
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="createUserForm.email"
                    required
                />
                <InputError class="mt-2" :message="createUserForm.errors.email" />
            </div>

            <div>
                <InputLabel for="password">{{ $t('Password') }}<span class="text-red-500">*</span></InputLabel>
                <InputField
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="createUserForm.password"
                    required
                />
                <InputError class="mt-2" :message="createUserForm.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation">{{ $t('Confirm password') }}<span class="text-red-500">*</span></InputLabel>

                <InputField
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="createUserForm.password_confirmation"
                    required
                />

                <InputError class="mt-2" :message="createUserForm.errors.password_confirmation" />
            </div>

            <div>
                <InputLabel for="locale">{{ $t('Language') }}</InputLabel>
                <SelectField id="locale"
                             name="locale"
                             :options="localeOptions"
                             v-model="createUserForm.locale" />
                <InputError class="mt-2" :message="createUserForm.errors.locale" />
            </div>

            <div>
                <InputLabel for="timezone">{{ $t('Timezone') }}</InputLabel>
                <select class="border border-gray-300 mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors ease-out"
                        id="timezone"
                        name="timezone"
                        v-model="createUserForm.timezone">
                    <template v-for="timezone in props.timezone_list">
                        <option :value="timezone">{{ timezone }}</option>
                    </template>
                </select>
                <InputError class="mt-2" :message="createUserForm.errors.locale" />
            </div>

            <div>
                <InputLabel for="date_format">{{ $t('Date format') }}</InputLabel>
                <SelectField id="date_format"
                             name="date_format"
                             :options="dateOptions"
                             :locale="$page.props.auth.user.settings.locale"
                             v-model="createUserForm.date_format" />
                <InputError class="mt-2" :message="createUserForm.errors.date_format" />
            </div>

            <div>
                <InputLabel for="time_format">{{ $t('Time format') }}</InputLabel>
                <SelectField id="time_format"
                             name="time_format"
                             :options="timeOptions"
                             :locale="$page.props.auth.user.settings.locale"
                             v-model="createUserForm.time_format" />
                <InputError class="mt-2" :message="createUserForm.errors.time_format" />
            </div>

            <section class="flex">
                <PrimaryButton type="submit" :disabled="createUserForm.processing">{{ $t('Submit') }}</PrimaryButton>
            </section>
        </form>
    </AuthenticatedLayout>
</template>
