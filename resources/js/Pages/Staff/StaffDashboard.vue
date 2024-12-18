<script setup>
import { Head } from '@inertiajs/vue3';
import StaffAuthenticatedLayout from '@/Layouts/StaffAuthenticatedLayout.vue';
</script>

<template>
    <Head title="Staff Dashboard" />

    <StaffAuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
            
        </template>

        
    </StaffAuthenticatedLayout>
</template>
