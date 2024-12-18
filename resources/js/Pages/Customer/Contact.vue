<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="border-b border-gray-100 bg-[#2D5522]"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dash')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                                <NavLink
                                    :href="route('dash')"
                                    class="ml-2 text-xl font-bold text-gray-900"
                                >
                                    COFI
                                </NavLink>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dash')"
                                >
                                    HOME
                                </NavLink>
                                <NavLink
                                    :href="route('menu')"
                                    
                                >
                                    MENU
                                </NavLink>
                                <NavLink
                                    :href="route('contact')"
                                    :active="route().current('contact')"
                                >
                                    CONTACT US
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dash')" :active="route().current('dash')">HOME</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('menu')" :active="route().current('menu')">MENU</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('contact')" :active="route().current('contact')">CONTACT US</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('cart')">MY CART</ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <section class="bg-gray-100 py-16 px-8">
                <div class="max-w-7xl mx-auto">
                  <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-8">
                    Contact Us
                  </h2>
                  <p class="text-center text-gray-600 mb-12">
                    Have questions or need assistance? Feel free to reach out to us using the form below.
                  </p>
              
                  <div class="grid md:grid-cols-2 gap-12">
                    <!-- Contact Information -->
                    <div class="space-y-6">
                      <h3 class="text-2xl font-semibold text-gray-800">Get in Touch</h3>
                      <p class="text-gray-600">
                        We’d love to hear from you! Here’s how you can reach us:
                      </p>
                      <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 4l7.89 5.26a2 2 0 002.22 0L21 12m-18 4l7.89 5.26a2 2 0 002.22 0L21 16" />
                        </svg>
                        <span class="text-gray-700">cofi@gmail.com</span>
                      </div>
                      <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 9h10l3-9h4M9 21h6M9 3h6M12 3v6" />
                        </svg>
                        <span class="text-gray-700">+639469717034</span>
                      </div>
                      <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v.01M12 3c4.41 0 8 1.79 8 4v4c0 2.21-3.59 4-8 4s-8-1.79-8-4V7c0-2.21 3.59-4 8-4zM4 15v2c0 2.21 3.59 4 8 4s8-1.79 8-4v-2" />
                        </svg>
                        <span class="text-gray-700">brgy. Ampayon, Butuan City, Agusan Del</span>
                      </div>
                    </div>
              
                    <!-- Contact Form -->
                    <form class="bg-white shadow-md rounded-lg p-6 space-y-6">
                      <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                        <input 
                          id="name" 
                          type="text" 
                          placeholder="Enter your name" 
                          class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                      </div>
                      <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Your Email</label>
                        <input 
                          id="email" 
                          type="email" 
                          placeholder="Enter your email" 
                          class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                      </div>
                      <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                        <textarea 
                          id="message" 
                          rows="4" 
                          placeholder="Write your message..." 
                          class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                      </div>
                      <button 
                        type="submit" 
                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Send Message
                      </button>
                    </form>
                  </div>
                </div>
              </section>
              
        </div>
    </div>

</template>
