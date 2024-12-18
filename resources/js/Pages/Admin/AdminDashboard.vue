<script setup>
import { Head } from '@inertiajs/vue3';
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminAuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Admin Dashboard
            </h2>
        </template>

    </AdminAuthenticatedLayout>
</template>
