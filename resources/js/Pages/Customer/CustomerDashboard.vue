<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { ref } from 'vue';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    prod:{
        type: Array,
        default: () => [],
    },
});

console.log(props.prod);

console.log(props.prod);
const showingNavigationDropdown = ref(false);

const form = useForm({
    id: 0,
    qty: 1,
});

const addToCart = (p_id) => {
    form.id = p_id;
    form.post('/cart/add');
    alert("added to cart successfully.");
    alert("you can edit you quantity in your cart.");   
};
</script>

<template>
    <Head title="COFI - Carry the Merry"/>
    
    <!-- Main Layout -->
    <div class="min-h-screen">
        <div class=" bg-[#2D5522]">
            <nav class="border-gray-100 text-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex ">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center ">
                                <Link :href="route('dash')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                                <NavLink :href="route('dash')" class="ml-2 text-xl font-bold text-gray-900">COFI</NavLink>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dash')" :active="route().current('dash')">HOME</NavLink>
                                <NavLink :href="route('menu')" class="me-20">MENU</NavLink>
                                <NavLink :href="route('contact')">CONTACT US</NavLink>
                            </div>
                        </div>

                        

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">   
                            <NavLink :href="route('transactions.page')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-right me-4">
                                    <path fill-rule="evenodd" d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM12 9.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5H12Zm-.75-2.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5H12a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5H6Zm-.75 3.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5H6a.75.75 0 0 1-.75-.75ZM6 6.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-3A.75.75 0 0 0 9 6.75H6Z" clip-rule="evenodd" />
                                    <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 0 1-3 0V6.75Z" />
                                </svg>
                            </NavLink>  
                            <NavLink :href="route('cart')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-right">
                                    <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                                </svg>
                            </NavLink>
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dash')" :active="route().current('dash')">HOME</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('menu')" :active="route().current('menu')">MENU</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('contact')" :active="route().current('contact')">CONTACT US</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('cart')" :active="route().current('cart')">MY CART</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('transactions.page')" :active="route().current('transactions.page')">TRANSACTION</ResponsiveNavLink>
                    </div>

                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-white">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative flex flex-col items-center md:mt-0">
                <div class="z-10 px-4 sm:px-6 max-w-xl text-center mb-12">
                    <h1 class="sm:text-5xl md:text-6xl tracking-tighter">
                        <img src="/images/CARRY THE MERRY.png" alt="" class="mx-auto">
                    </h1>
                </div>
            </div>            
        </div>
        <!-- lmited edition -->
        <div >
            <div class="bg-white text-white">
                <div class="text-center py-8">
                    <h1 class="text-5xl font-bold text-black font-serif">HOLIDAY LIMITED EDITION</h1>
                </div>
                <div class="flex flex-wrap justify-center gap-4 px-3">
                    <div v-for="product in prod.filter(prod => prod.category_id === 1 && prod.availability)" :key="product.id" class="rounded-lg pt-4 text-center shadow-lg w-[150px]">
                        <img :src="'storage/' + product.image" :alt="product.name" class="h-40 w-auto mx-auto mb-2" />
                        <h2 class="text-xs text-black font-bold">{{ product.name }}</h2>
                        <p class="text-xs text-black mb-2 font-semibold">₱{{ product.price }}</p>
                        <button @click="addToCart(product.id)" class="px-7 py-1 mb-4 text-white bg-[#2D5522] font-bold text-sm hover:bg-yellow-300 hover:text-black transition-all duration-300 ease-in-out rounded-b-lg">ADD TO CART</button>
                    </div>
                </div>
            </div>        
        </div>

        <!-- barista picks -->
        <div>
            <div class="bg-[#c7c2b7] min-h-screen text-white mt-20 md:px-10">
                <div class="py-8">
                    <h1 class="text-lg md:ms-20 font-bold text-black font-serif">BARISTA PICKS</h1>
                </div>
                <div class="flex flex-wrap justify-center gap-4 px-3">
                    <div v-for="(product, index) in prod.filter(prod => prod.category_id === 2 && prod.availability).slice(0, 4)" :key="product.id" class="rounded-lg pt-4 text-center shadow-lg w-[150px]">
                        <img :src="'storage/' + product.image" :alt="product.name" class="h-40 w-auto mx-auto mb-2" />
                        <h2 class="text-xs text-black font-bold">{{ product.name }}</h2>
                        <p class="text-xs text-black mb-2 font-semibold">₱{{ product.price }}</p>
                        <button @click="addToCart(product.id)" class="px-7 py-1 mb-4 bg-black text-white font-bold text-sm hover:bg-yellow-300 hover:text-black transition-all duration-300 ease-in-out rounded-b-lg">ADD TO CART</button>
                    </div>
                    <div class="flex justify-center mt-10 inline-block">
                        <NavLink :href="route('menu')" class="inline-flex items-center ">
                            <i class="text-black font-bold underline text-sm hover:text-white transition-all duration-200 ease-in-out mb-10">SEE MORE &rarr;</i>
                        </NavLink>
                    </div>
                </div>
            </div>        
        </div>

        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-between">
                    <div class="w-full sm:w-1/2 md:w-1/3 mb-6 md:mb-0">
                        <h3 class="text-xl font-bold mb-2">COFI</h3>
                        <p class="text-gray-400">Brewing happiness, one cup at a time.</p>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 mb-6 md:mb-0">
                        <h3 class="text-xl font-bold mb-2">Quick Links</h3>
                        <div class="flex flex-col space-y-4">
                          <NavLink :href="route('dash')" :active="route().current('dash')" class="text-gray-400 hover:text-white">HOME</NavLink>
                          <NavLink :href="route('menu')" :active="route().current('menu')"  class="text-gray-400 hover:text-white">MENU</NavLink>
                          <NavLink :href="route('contact')" :active="route().current('contact')"  class="text-gray-400 hover:text-white">CONTACT US</NavLink>
                          <NavLink :href="route('cart')" :active="route().current('cart')" class="text-gray-400 hover:text-white">MY CART</NavLink>
                          
                        </div>
                      </div>
                      
                    <div class="w-full sm:w-1/2 md:w-1/3">
                        <h3 class="text-xl font-bold mb-2">FOLLOW US</h3>
                        <div class="flex space-x-4">
                          <!--FACEBOOK-->
                            <a href="#" class="text-gray-400 hover:text-white">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22  12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <!--INSTAGRAM-->
                            <a href="#" class="text-gray-400 hover:text-white">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <!--TWITTER-->
                            <a href="#" class="text-gray-400 hover:text-white">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center text-gray-400 text-sm">
                    <p>© 2024 COFI. All rights reserved.</p>
                    <p>Developed by Rocales | Puspus | Prisco.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
