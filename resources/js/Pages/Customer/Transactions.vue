<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import { ChevronDownIcon, ChevronUpIcon } from 'lucide-vue-next';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';


const showingNavigationDropdown = ref(false);
const transactions = ref([]);
const sortField = ref('created_at');
const sortDirection = ref('desc');

const props = defineProps({
  transactions: {
    type: Array,
    default: () => []
  }
});

console.log(props.transactions);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
};

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
  
  transactions.value.sort((a, b) => {
    let comparison = 0;
    if (a[field] > b[field]) comparison = 1;
    if (a[field] < b[field]) comparison = -1;
    return sortDirection.value === 'desc' ? comparison * -1 : comparison;
  });
};

const getSortIcon = (field) => {
  if (sortField.value !== field) return null;
  return sortDirection.value === 'asc' ? ChevronUpIcon : ChevronDownIcon;
};
</script>

<template>
    <Head title="COFI - transactions"/>
            <nav class="bg-[#2D5522] text-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex ">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center ">
                                <NavLink :href="route('dash')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </NavLink>
                                <NavLink :href="route('dash')" class="ml-2 text-xl font-bold text-gray-900">COFI</NavLink>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dash')" :active="route().current('dash')">HOME</NavLink>
                                <NavLink :href="route('menu')" :active="route().current('menu')" class="me-20">MENU</NavLink>
                                <NavLink :href="route('contact')" :active="route().current('contact')">CONTACT US</NavLink>
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
                        <ResponsiveNavLink :href="route('transactions.page')" :active="route().current('transactions.page')">TRANSACTIONS</ResponsiveNavLink>

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
  <div class="container mx-auto p-4 sm:p-6">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800">Your Transactions</h1>

    <!-- No transactions message -->
    <div v-if="props.transactions.length === 0" class="text-center text-gray-500 bg-white p-8 rounded-lg shadow-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
      </svg>
      <p class="text-xl font-semibold">No transactions found.</p>
      <p class="mt-2">Your transaction history will appear here once you make a transaction.</p>
    </div>

    <!-- Transactions Table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden hidden lg:block">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th @click="sortBy('total_amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center">
                  Total Amount
                  <component :is="getSortIcon('total_amount')" v-if="getSortIcon('total_amount')" class="ml-1 h-4 w-4" />
                </div>
              </th>
              <th @click="sortBy('Payment Method')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center">
                  Payment Method
                  <component :is="getSortIcon('total_amount')" v-if="getSortIcon('total_amount')" class="ml-1 h-4 w-4" />
                </div>
              </th>
              <th @click="sortBy('gcash_no')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center">
                  Gcash Number
                  <component :is="getSortIcon('gcash_no')" v-if="getSortIcon('gcash_no')" class="ml-1 h-4 w-4" />
                </div>
              </th>
              <th @click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center">
                  Status
                  <component :is="getSortIcon('status')" v-if="getSortIcon('status')" class="ml-1 h-4 w-4" />
                </div>
              </th>
              <th @click="sortBy('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center">
                  Date
                  <component :is="getSortIcon('created_at')" v-if="getSortIcon('created_at')" class="ml-1 h-4 w-4" />
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="transaction in props.transactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱{{ transaction.total_amount }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.payment_method }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.gcash_no || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                    'bg-green-100 text-green-800': transaction.status === 'completed',
                    'bg-gray-100 text-gray-800': transaction.status !== 'pending' && transaction.status !== 'completed'
                  }"
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ transaction.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile-friendly list view -->
    <div v-if="props.transactions.length > 0" class="lg:hidden mt-4 space-y-4">
      <div v-for="transaction in props.transactions" :key="transaction.id" class="bg-white shadow rounded-lg p-4">
        <div class="flex justify-between items-center mb-2">
          <span class="font-semibold text-gray-900">${{ transaction.total_amount }}</span>
          <span
            :class="{
              'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
              'bg-green-100 text-green-800': transaction.status === 'completed',
              'bg-gray-100 text-gray-800': transaction.status !== 'pending' && transaction.status !== 'completed'
            }"
            class="px-2 py-1 rounded-full text-xs font-semibold"
          >
            {{ transaction.status }}
          </span>
        </div>
        <div class="text-sm text-gray-600">Gcash: {{ transaction.gcash_no || 'N/A' }}</div>
        <div class="text-sm text-gray-500 mt-1">{{ formatDate(transaction.created_at) }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.fade-in {
  animation: fadeIn 0.5s ease-out;
}
</style>