<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { ref, computed } from 'vue';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';


const props = defineProps({
  cart: {
    type: Array,
    default: () => [],
  },
});


console.log(props.cart);
const showingNavigationDropdown = ref(false);
const taxRate = 0.12;


const showModal = ref(false); // For controlling modal visibility
const paymentMethod = ref(''); // Store the selected payment method
const gcashNumber = ref(''); // Store the GCash mobile number


const subtotal = computed(() => {
  return props.cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
});

const tax = computed(() => {
  return subtotal.value * taxRate;
});

const total = computed(() => {
  return subtotal.value + tax.value;
});

// Update quantity in the frontend and database
const updateQuantity = async (item, newQuantity) => {
  if (newQuantity > 0) {
    console.log(newQuantity);
    try {
      // Optimistic updatea
      item.quantity = newQuantity;

      // Send the updated quantity to the backend
      const response = await fetch(`/cart/${item.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // Laravel CSRF token
        },
        body: JSON.stringify({ quantity: newQuantity }),
      });

      if (!response.ok) {
        throw new Error('Failed to update quantity');
      }

      // Fetch the latest quantity and price from the backend
      const data = await response.json();
      item.quantity = data.quantity; // Backend-confirmed quantity
      item.total_amount = data.updated_price; // Backend-confirmed price
    } catch (error) {
      // Revert changes on error
      console.error('Error updating quantity:', error);
    }
  }
};

const form = useForm({
  gcashNumber: '',
  paymentMethod: '',
  total: 0,
});

// Update and submit the form
const submitPayment = () => {
  try {
    // Directly update the form properties
    form.paymentMethod = paymentMethod.value;
    form.gcashNumber = paymentMethod.value === 'gcash' ? gcashNumber.value : null;
    form.total = total.value;

    // Post request with Inertia form
    form.post('/transactions/pay', {
      onSuccess: () => {
        alert('Transaction saved successfully!');
        showModal.value = false;
        form.reset(); // Reset the form after success if needed
      },
      onError: (errors) => {
        console.error('Failed to save transaction:', errors);
        alert('Failed to save transaction.');
      },
    });
    alert('your order has been processed');
  } catch (error) {
    console.error('Error submitting transaction:', error);
    alert('An error occurred while saving the transaction.');
  }
};


const removeItem = async (itemId) => {
    // Optimistically remove the item from the cart
    const index = props.cart.findIndex(item => item.cart_id === itemId);
    if (index !== -1) {
      props.cart.splice(index, 1);
    }

    try {
      // Send DELETE request to the backend
      const response = await fetch(`/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // Laravel CSRF token
        },
      });

      if (!response.ok) {
        throw new Error('Failed to remove item');
      }

      // Optionally, handle any response from the backend, e.g., update or confirm the deletion.
    } catch (error) {
      // Handle error if needed and revert the UI update if the delete fails
      console.error('Error removing item:', error);
      // Optionally, you can undo the optimistic update if the backend request fails
    }
    alert('successfully removed!');
    window.location.href = '/cart';

};




</script>


<template>
  <div class="min-h-screen bg-gradient-to-br bg-[#fdfefd]">
    <Head title="COFI - Your Cart" />
    
    <!-- Navigation -->
    <nav class="bg-[#2D5522] text-white">
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
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-right">
                    <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                </svg>
              </div>
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
            <ResponsiveNavLink :href="route('cart')">MY CART</ResponsiveNavLink>
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

  <main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
      <!-- Cart Summary -->
      <div class="lg:w-2/3 bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="p-6 bg-[#2D5522] text-white">
          <h2 class="text-2xl font-bold">Your Cart</h2>
        </div>
        <ul class="divide-y divide-gray-200">
          <li
            v-for="item in props.cart"
            :key="item.id"
            class="p-6 flex flex-wrap lg:flex-nowrap items-center space-y-4 lg:space-y-0 space-x-5"
          >
            <!-- Product Image -->
            <img
              :src="'/storage/' + item.image"
              :alt="item.product_name"
              class="h-20 w-20 lg:h-24 lg:w-24 rounded-lg object-cover shadow-md flex-shrink-0"
            />
            
            <!-- Product Details -->
            <div class="flex-1 min-w-[150px]">
              <h3 class="text-base lg:text-lg font-semibold text-gray-800">
                {{ item.product_name }}
              </h3>
              <p class="text-sm text-gray-600">{{ item.description }}</p>
            </div>
            
            <!-- Hidden Input for Product ID -->
            <input type="number" :v-model="product_id" :value="item.product_id" class="hidden" id="">




            <!-- Quantity Controls -->
            <div class="flex items-center space-x-2 lg:space-x-5">
              <button
                @click="updateQuantity(item, item.quantity - 1)"
                class="text-gray-500 hover:text-[#2D5522] transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              
              <input
                type="number"
                class="text-sm lg:text-lg font-medium text-center w-12 lg:w-16 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2D5522] focus:border-[#2D5522] appearance-none [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                v-model.number="item.quantity"
                @change="item.quantity"
              /> 
              
              <button
                @click="updateQuantity(item, item.quantity + 1)"
                class="text-gray-500 hover:text-[#2D5522] transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </button>
            </div>
            
            <!-- Product Price -->
            <p class="text-sm lg:text-lg font-semibold text-gray-800 min-w-[80px] text-center lg:ms-10 lg:ps-5">
              ₱{{ item.price * item.quantity }}
            </p>
            
            <!-- Remove Item -->
            <button
              @click="removeItem(item.id)"
              class="text-red-500 hover:text-red-700 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </li>
        </ul>
      </div>

      <!-- Order Summary -->
      <div class="lg:w-1/3 bg-white rounded-lg shadow-xl">
        <div class="p-6 bg-[#2D5522] text-white">
          <h2 class="text-xl font-bold">Order Summary</h2>
        </div>
        <div class="p-6 space-y-4">
          <div class="flex justify-between">
            <p class="text-sm lg:text-base font-medium">Subtotal:</p>
            <p class="text-sm lg:text-base font-semibold">₱{{ subtotal }}</p>
          </div>
          <div class="flex justify-between">
            <p class="text-sm lg:text-base font-medium">Tax (12%):</p>
            <p class="text-sm lg:text-base font-semibold">₱{{ tax.toFixed(2) }}</p>
          </div>
          <div class="flex justify-between text-lg lg:text-xl font-bold">
            <p>Total:</p>
            <p>₱{{ total }}</p>
          </div>
          <button
            @click="showModal = true"
            class="w-full bg-[#2D5522] text-white py-2 rounded-md text-sm lg:text-base font-semibold hover:bg-[#22451a] transition duration-200"
          >
            Proceed to Checkout
          </button>
        </div>
      </div>
    </div>
  </main>

    <!-- Payment Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-96 p-6">
      <h3 class="text-2xl font-semibold mb-4">Payment Information</h3>
      <form @submit.prevent="submitPayment">
        <div class="mb-4">
          <label for="payment-method" class="block text-sm font-medium text-gray-700">Payment Method</label>
          <select id="payment-method" v-model="paymentMethod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">Select Payment Method</option>
            <option value="gcash">GCash</option>
            <option value="cod">Cash on Delivery</option>
          </select>
        </div>

        <!-- GCash Number input, shown only if GCash is selected -->
        <div v-if="paymentMethod === 'gcash'" class="mb-4">
          <label for="gcash-number" class="block text-sm font-medium text-gray-700">GCash Mobile Number</label>
          <input
            id="gcash-number"
            type="text"
            v-model="gcashNumber"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your GCash mobile number"
          />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Total</label>
          <p class="text-lg font-semibold">₱{{ total.toFixed(2) }}</p>
          <input type="hidden" v-model="total" value="{{ total.toFixed(2)}}" name="total" />
        </div>

        <div class="mt-4">
          <button type="submit" class="w-full bg-[#FFD700] hover:bg-[#F4C430] text-[#2D5522] font-bold py-3 rounded-lg transition duration-300">
            Submit Payment
          </button>
        </div>
      </form>
    </div>
  </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.container {
  animation: fadeIn 0.5s ease-out;
}
</style>
