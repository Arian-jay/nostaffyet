<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
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

const showModal = ref(false);
const modalMessage = ref('');

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const handleGoogleLogin = () => {
    window.location.href = route('google.redirect');
};
</script>

<template>

    <Head title="Log in" />

    <div class="flex min-h-screen items-center justify-center bg-white p-4 sm:p-6">
        <div class="w-full max-w-6xl overflow-hidden rounded-lg bg-white shadow-lg lg:flex">
            <!-- Left side - Login Form -->
            <div class="w-full p-8 sm:p-12 lg:w-1/2 border border-black rounded">


                <div class="flex flex-col items-center justify-center text-center">
                    <h1 class="mb-2 text-3xl font-bold sm:text-4xl">Welcome back!</h1>
                    <p class="mb-8 text-base text-gray-600 sm:text-lg">
                        Your favorite brew is just a click away.<br />
                        Log in and let's get you caffeinated!
                    </p>
                </div>

                <form @submit.prevent="submit">
                    <div class="mt-4">
                        <TextInput id="email" type="email" class="mt-1 block w-full rounded-full" v-model="form.email"
                            required autofocus autocomplete="username" placeholder="Email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <TextInput id="password" type="password" class="mt-1 block w-full rounded-full"
                            v-model="form.password" required autocomplete="current-password" placeholder="Password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div
                        class="mt-4 flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>



                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Forgot your password?
                        </Link>
                    </div>

                    <div v-if="status" class="mb-4 text-sm font-medium text-red-600">
                        {{ status }}
                    </div>

                    <div class="mt-4 space-y-4 justify-center">
                        <PrimaryButton
                            class="w-full items-center justify-center rounded-full bg-black py-2 text-center text-white hover:bg-gray-800 sm:py-3 { 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Login
                        </PrimaryButton>

                        <button type="button" @click="handleGoogleLogin"
                            class="flex w-full items-center justify-center rounded-full border border-gray-300 bg-white py-2 text-center text-black hover:bg-gray-200 sm:py-3">
                            <img src="/images/google-logo.svg" alt="Google Logo" class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                            Sign in with Google
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <span class="text-gray-600">Not a member? </span>
                        <Link :href="route('register')" class="font-semibold text-black hover:underline">
                        Register now
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Right side - Illustration -->
            <div class="hidden w-full bg-gray-50 lg:block lg:w-1/2">
                <img src="/images/COFI building.png" alt="Coffee Shop Illustration" class="h-[500px] w-auto mx-auto" />
            </div>
        </div>
    </div>
</template>
