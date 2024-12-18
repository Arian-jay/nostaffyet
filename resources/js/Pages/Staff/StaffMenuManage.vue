<template>
    <Head title="Staff Menu Management" />
  
    <StaffAuthenticatedLayout>
      <div class="min-h-screen bg-gradient-to-br from-emerald-900 to-forest-green text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r bg-gray-100 mb-12 text-center">
             Menu Management
          </h1>
  
          <!-- Snowfall effect -->
          <div class="snowfall"></div>
  
          <!-- Success Message -->
          <transition name="fade">
            <div v-if="successMessage"
              class="bg-emerald-800 border-l-4 border-gold-500 text-gold-100 p-4 mb-8 rounded-md shadow-lg">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-gold-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium">
                    {{ successMessage }}
                  </p>
                </div>
              </div>
            </div>
          </transition>
  
          <!-- Menu Items by Category -->
          <div v-for="(items, category) in sortedMenuItems" :key="category" class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-gold-400 border-b border-emerald-500 pb-2 flex items-center">
              <span class="mr-3">🎄</span>{{ category }}<span class="ml-3">🎄</span>
            </h2>
  
            <div class="bg-forest-green rounded-lg shadow-2xl overflow-hidden border border-emerald-500">
              <table class="min-w-full divide-y divide-emerald-700">
                <thead class="bg-emerald-900 sticky top-0">
                  <tr>
                    <th v-for="header in ['Name', 'Price', 'Quantity', 'Availability', 'Action']" :key="header"
                      class="px-6 py-4 text-left text-xs font-medium text-gold-400 uppercase tracking-wider border border-emerald-500 ">
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-emerald-800 ">
                  <tr v-for="item in items" :key="item.id"
                  class="transition-colors duration-300 ease-in-out bg-emerald-800 border-y-2 border-emerald-500">

                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white border-y-2 border-emerald-500">{{ item.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 border-y-2 border-emerald-500">₱ {{ item.price}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 border-y-2 border-emerald-500">{{ item.qty }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 border-y-2 border-emerald-500">
                      <span
                        :class="{ 'bg-emerald-700 text-emerald-100': item.availability, 'bg-red-800 text-red-100': !item.availability }"
                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-300 ">
                        {{ item.availability ? 'Available' : 'Unavailable' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-y-2 border-emerald-500">
                      <button @click="editMenu(item.id)"
                        class="text-gold-400 hover:text-gold-300 bg-emerald-800 hover:bg-emerald-700 px-4 py-2 rounded-md transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-opacity-50">
                        <i class="fas fa-gift mr-2"></i> Update
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
  
          <!-- Edit Menu Modal -->
          <transition name="modal">
            <div v-if="showEditModal"
              class="fixed inset-0 flex justify-center items-center z-50 backdrop-blur-sm backdrop-filter">
              <div class="bg-emerald-900 rounded-lg shadow-2xl w-full max-w-md mx-4 border border-gold-600"
                :class="{ 'scale-95 opacity-0': !showEditModal, 'scale-100 opacity-100': showEditModal }">
                <div class="px-6 py-4 border-b border-emerald-700 flex justify-between items-center">
                  <h3 class="text-2xl font-semibold text-gold-400">Edit Festive Item</h3>
                  <button @click="cancelEdit"
                    class="text-gray-400 hover:text-gray-200 transition-colors duration-300">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <form @submit.prevent="updateMenu" class="px-6 py-4 space-y-6">
                  <div v-for="field in ['name', 'category', 'price']" :key="field" class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">{{ field.charAt(0).toUpperCase() + field.slice(1) }}</label>
                    <p class="text-white bg-emerald-800 rounded-md p-3 font-semibold">
                      {{ field === 'price' ? '₱ ' + editForm[field] : editForm[field] }}
                    </p>
                  </div>
  
                  <div class="space-y-2">
                    <label for="qty" class="block text-sm font-medium text-gray-300">Quantity</label>
                    <input v-model="editForm.qty" type="number" id="qty"
                      class="block w-full bg-emerald-800 border-emerald-700 rounded-md shadow-sm focus:ring-gold-500 focus:border-gold-500 sm:text-sm text-white transition-all duration-300"
                      placeholder="Enter quantity" />
                  </div>
                  
                  <div class="space-y-2">
                    <label for="availability" class="block text-sm font-medium text-gray-300">Availability</label>
                    <div class="relative">
                      <select v-model="editForm.availability" id="availability"
                        class="block w-full pl-3 pr-10 py-2 text-base bg-emerald-800 border-emerald-700 focus:outline-none focus:ring-gold-500 focus:border-gold-500 sm:text-sm rounded-md appearance-none text-white transition-all duration-300">
                        <option :value="true">Available</option>
                        <option :value="false">Unavailable</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                      </div>
                    </div>
                  </div>
  
                  <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="cancelEdit"
                      class="px-4 py-2 border border-emerald-600 rounded-md text-sm font-medium text-gray-300 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-all duration-300">
                      Cancel
                    </button>
                    <button type="submit"
                      class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-emerald-900 bg-gold-500 hover:bg-gold-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-all duration-300">
                      Update
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </transition>
  
        </div>
      </div>
    </StaffAuthenticatedLayout>
  </template>
  
  <script setup>
  import { Head } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import StaffAuthenticatedLayout from '@/Layouts/StaffAuthenticatedLayout.vue';
  
  const props = defineProps({
    menuItems: Object,
    success: String,
  });
  
  const showEditModal = ref(false);
  const editForm = useForm({
    id: null,
    name: '',
    price: 0,
    qty: 0,
    category: '',
    availability: true,
  });
  const successMessage = ref(props.success || '');
  
  const categoryOrderMap = {
    'Holiday Limited Edition': 1,
    'Coffee Beverage': 2,
    'Foods & Snacks': 3,
    'Coffee Beans & Ground Coffee': 4,
  };
  
  const sortedMenuItems = computed(() => {
    const entries = Object.entries(props.menuItems);
    entries.sort(([categoryA], [categoryB]) => {
      const orderA = categoryOrderMap[categoryA] || Infinity;
      const orderB = categoryOrderMap[categoryB] || Infinity;
      return orderA - orderB;
    });
    return Object.fromEntries(entries);
  });
  
  const editMenu = (id) => {
    const item = findItemById(id);
    if (item) {
      editForm.id = item.id;
      editForm.name = item.name;
      editForm.price = item.price;
      editForm.qty = item.qty;
      editForm.category = item.category;
      editForm.availability = item.availability;
      showEditModal.value = true;
    }
  };
  
  const updateMenu = () => {
    editForm.put(route('staff.menu.update', editForm.id), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        showEditModal.value = false;
        successMessage.value = 'Festive item updated successfully! 🎄';
        setTimeout(() => {
          successMessage.value = '';
        }, 3000);
      },
      onError: () => {
        successMessage.value = '';
        alert('Error updating festive item. Please try again.');
      },
    });
  };
  
  const cancelEdit = () => {
    showEditModal.value = false;
    editForm.reset();
  };
  
  const findItemById = (id) => {
    for (const category in props.menuItems) {
      const item = props.menuItems[category].find((item) => item.id === id);
      if (item) return item;
    }
    return null;
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap');
  
  :root {
    --color-gold-400: #ffd700;
    --color-gold-500: #ffcc00;
    --color-gold-600: #ffb300;
    --color-gold-700: #ffa000;
    --color-forest-green: #228B22;
    --color-emerald-700: #047857;
    --color-emerald-800: #065f46;
    --color-emerald-900: #064e3b;
  }
  
  body {
    font-family: 'Poppins', sans-serif;
  }
  
  h1, h2, h3, h4, h5, h6 {
    font-family: 'Playfair Display', serif;
  }
  
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  
  .modal-enter-active,
  .modal-leave-active {
    transition: all 0.3s ease;
  }
  
  .modal-enter-from,
  .modal-leave-to {
    opacity: 0;
    transform: scale(0.9);
  }
  
  @media (max-width: 640px) {
    table {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
    }
  }
  
  /* Custom scrollbar for Webkit browsers */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }
  
  ::-webkit-scrollbar-track {
    background: var(--color-emerald-900);
  }
  
  ::-webkit-scrollbar-thumb {
    background: var(--color-gold-600);
    border-radius: 4px;
  }
  
  ::-webkit-scrollbar-thumb:hover {
    background: var(--color-gold-500);
  }
  
  /* Snowfall effect */
  .snowfall {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    background-image: 
      radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.8) 1px, transparent 1px),
      radial-gradient(circle at 25% 75%, rgba(255, 255, 255, 0.8) 1px, transparent 1px),
      radial-gradient(circle at 75% 25%, rgba(255, 255, 255, 0.8) 1px, transparent 1px);
    background-size: 100px 100px;
    animation: snowfall 10s linear infinite;
  }
  
  @keyframes snowfall {
    0% {
      background-position: 0 0, 0 0, 0 0;
    }
    100% {
      background-position: 100px 100px, 50px 50px, -50px -50px;
    }
  }
  
  /* Festive decorations */
  .container::before,
  .container::after {
    content: '🎅';
    position: absolute;
    font-size: 2rem;
    top: 5rem;
  }
  
  .container::before {
    left: 1rem;
  }
  
  .container::after {
    right: 1rem;
  }
  
  h2::before,
  h2::after {
    content: '🎄';
    display: inline-block;
    margin: 0 0.5rem;
    animation: treeWiggle 2s ease-in-out infinite;
  }
  
  @keyframes treeWiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(5deg); }
    75% { transform: rotate(-5deg); }
  }
  
  button {
    position: relative;
    overflow: hidden;
  }
  
  button::after {
    content: '❄️';
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 1.5rem;
    opacity: 0;
    transition: top 0.3s, opacity 0.3s;
  }
  
  button:hover::after {
    top: calc(50% - 0.75rem);
    opacity: 1;
  }
  </style>
  
  