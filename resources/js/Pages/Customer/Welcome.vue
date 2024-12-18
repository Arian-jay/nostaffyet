<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const products = [
    {
        name: 'DARK HOT CHOCOLATE',
        description: 'Bold, smooth, and luxuriously rich',
        image: '/images/products/promo/dark-hot-chocolate.png',
        price:'25.00'
    },
    {
        name: 'PEPPERMINT COCOA',
        description: 'Rich, creamy cocoa with a refreshing peppermint twist',
        image: '/images/products/promo/peppermint-cocoa.png',
        price:'25.00'
    },
    {
        name: 'BISCOFF CREAM',
        description: 'A creamy coffee delight infused with the warm, spiced sweetness of Biscoff',
        image: '/images/products/promo/biscoff-cream.png',
        price:'25.00'
    }
];

const latte = [
    {
        name: 'Classic Black Coffee',
        image: '/images/products/latte/CLASSIC BLACK COFFEE.png',
        price: '25.00'
    },
    {
        name: 'Caramel Macchiato',
        image: '/images/products/latte/CLASSIC CARAMEL MACCHIATO.png',
        price: '25.00'
    },
    {
        name: 'Hot Cocoa',
        image: '/images/products/latte/CLASSIC HOT COCOA.png',
        price: '25.00'
    },
    {
        name: 'Classic Latte',
        image: '/images/products/latte/CLASSIC LATTE.png',
        price: '25.00'
    },
    {
        name: 'Matcha Latte',
        image: '/images/products/latte/MATCHA LATTE.png',
        price: '25.00'
    },
    {
        name: 'Strawberry Latte',
        image: '/images/products/latte/STRAWBERRY LATTE.png',
        price: '25.00'
    },

];
</script>

<template>

    <Head title="COFI - Carry the Merry" />

    <div class="min-h-screen bg-[#2D5522] text-white overflow-hidden">
        <!-- Navigation -->
        <div class="w-full px-4 sm:px-6 mb-10">
            <header class="flex flex-wrap items-center justify-between py-6">
                <!-- Logo -->
                <div class="flex shrink-0 items-center ">
                    <Link :href="route('dashboard')">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                    </Link>
                    <NavLink :href="route('dashboard')" class="ml-2 text-xl font-bold text-gray-900">COFI</NavLink>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-md hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>

                <!-- Auth Navigation -->
                <nav v-if="canLogin"
                    class="w-full md:w-auto mt-4 md:mt-0 flex flex-wrap items-center justify-center md:justify-end space-y-2 md:space-y-0 space-x-0 md:space-x-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="w-full md:w-auto rounded-md px-4 py-2 text-white ring-1 ring-white/20 transition hover:bg-white/10 text-center">
                    Dashboard
                    </Link>

                    <template v-else>
                        <Link :href="route('login')"
                            class="w-full md:w-auto rounded-md px-4 py-2 text-white ring-1 ring-white/20 transition hover:bg-white/10 text-center">
                        Log in
                        </Link>

                        <Link v-if="canRegister" :href="route('register')"
                            class="w-full md:w-auto rounded-md px-4 py-2 text-white ring-1 ring-white/20 transition hover:bg-white/10 text-center">
                        Register
                        </Link>
                    </template>
                </nav>
            </header>

            <!-- Hero Section -->
            <div class="relative flex flex-col items-center justify-center min-h-[100px] mt-2 md:mt-0 pt-2">
                <!-- Text Content -->
                <div class="z-10 px-2 sm:px-6 max-w-lg text-center">
                    <!-- Image -->
                    <img src="/images/CARRY THE MERRY.png" alt="Carry The Merry" class="mx-auto mb-6">
            
                    <!-- Button -->
                    <Link :href="route('login')"
                        class="rounded-full border-2 border-white px-6 py-2 sm:px-8 sm:py-3 font-semibold uppercase tracking-wider transition hover:bg-white hover:text-[#2D5522]">
                        Order Now
                    </Link>
                </div>
            </div>
            
        </div>
        <div>
            <div class="bg-white text-white">
                <div class="text-center py-8">
                    <h1 class="text-5xl font-bold text-black font-serif">HOLIDAY LIMITED EDITION</h1>
                </div>
                <div class="flex flex-wrap justify-center gap-4 px-3">
                    <div v-for="product in products" :key="product.id" class="rounded-lg pt-4 text-center shadow-lg w-[150px]">
                        <img :src="product.image" :alt="product.name" class="h-40 w-auto mx-auto mb-2" />
                        <h2 class="text-xs text-black font-bold">{{ product.name }}</h2>
                        <p class="text-xs text-black mb-2 font-semibold">₱{{ product.price }}</p>
                        <button :href="route('login')" class="px-7 py-1 mb-4 text-white bg-[#2D5522] font-bold text-sm hover:bg-yellow-300 hover:text-black transition-all duration-300 ease-in-out rounded-b-lg">ADD TO CART</button>
                    </div>
                </div>
            </div>        
        </div>
        <div>
            <div class="bg-[#c7c2b7] min-h-screen text-white pt-10 md:px-10">
                <div>
                    <h1 class="text-lg md:ms-20 font-bold text-black font-serif">BARISTA PICKS</h1>
                </div>
                <div class="flex flex-wrap justify-center gap-4 px-3">
                    <div v-for="(product, index) in latte.slice(0, 4)" :key="product.id" class="rounded-lg pt-4 text-center shadow-lg w-[150px]">
                        <img :src="product.image" :alt="product.name" class="h-40 w-auto mx-auto mb-2" />
                        <h2 class="text-xs text-black font-bold">{{ product.name }}</h2>
                        <p class="text-xs text-black mb-2 font-semibold">₱{{ product.price }}</p>
                        <button :href="route('login')" class="px-7 py-1 mb-4 bg-black text-white font-bold text-sm hover:bg-yellow-300 hover:text-black transition-all duration-300 ease-in-out rounded-b-lg">ADD TO CART</button>
                    </div>
                    <div class="flex justify-center mt-10 inline-block">
                        <Link :href="route('menu')" class="inline-flex items-center">
                            <i class="text-black font-bold underline text-sm hover:text-white transition-all duration-200 ease-in-out mb-10">SEE MORE &rarr;</i>
                        </Link>
                    </div>                    
                </div>
            </div>        
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}
</style>