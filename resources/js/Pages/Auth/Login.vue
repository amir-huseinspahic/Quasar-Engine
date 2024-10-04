<script setup>
    import { Head, useForm } from '@inertiajs/vue3'

    import InputLabel from '@/Components/Base/InputLabel.vue'
    import InputField from '@/Components/Base/InputField.vue'
    import InputError from '@/Components/Base/InputError.vue'
    import PrimaryButton from '@/Components/Base/PrimaryButton.vue'
    import Checkbox from '@/Components/Base/Checkbox.vue'

    const props = defineProps({
        canResetPassword: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <Head title="Login" />

    <div class="w-screen h-screen flex flex-col items-center justify-center bg-gray-100">
        <form class="space-y-4 w-full sm:w-1/2 lg:w-1/3 bg-white shadow-lg rounded-lg p-4" @submit.prevent="submit">
            <div>
                <InputLabel for="email" :value="$t('E-mail')" />
                <InputField
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div>
                <InputLabel for="password" :value="$t('Password')" />
                <InputField
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $t('Remember me') }}</span>
                </label>
            </div>

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ $t('Log in') }}
            </PrimaryButton>
        </form>
    </div>
</template>
