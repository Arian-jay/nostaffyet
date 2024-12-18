<template>
    <AdminAuthenticatedLayout>
      <div class="p-6 bg-gradient-to-br from-indigo-50 to-white shadow-lg rounded-lg">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Order Management</h2>
  
        <!-- Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Orders</h3>
            <p class="text-2xl font-bold text-indigo-600">{{ filteredOrders.length }}</p>
          </div>
          <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Revenue</h3>
            <p class="text-2xl font-bold text-green-600">₱{{ totalRevenue.toFixed(2) }}</p>
          </div>
          <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Average Order Value</h3>
            <p class="text-2xl font-bold text-blue-600">₱{{ averageOrderValue.toFixed(2) }}</p>
          </div>
        </div>
  
        <!-- Search and Filter Section -->
        <div class="mb-4 flex items-center">
          <div class="relative flex-grow">
            <input v-model="searchQuery" type="text" placeholder="Search orders..."
              class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <svg xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
  
        <!-- Table Section -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Details
                  </th>
                  <th v-for="header in ['Order ID', 'Customer', 'Total', 'Status', 'Date', 'Actions']"
                    :key="header" scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition duration-150 ease-in-out"
                    @click="header !== 'Actions' && toggleSort(header.toLowerCase())">
                    {{ header }}
                    <span v-if="sortBy === header.toLowerCase() && header !== 'Actions'" class="ml-1">
                      <svg v-if="!sortDesc" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 15l7-7 7 7" />
                      </svg>
                      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                      </svg>
                    </span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <template v-for="order in paginatedOrders.filter(order => order.status !== 'Completed')" :key="order.id">
                  <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                    <td class="px-4 py-3 whitespace-nowrap">
                      <button @click="toggleOrderDetails(order.id)" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ order.id }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                      {{ order.customer_name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                      ₱{{ parseFloat(order.total_amount).toFixed(2) }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                      <span
                        :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', statusColor(order.status)]">
                        {{ order.status }}
                      </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                      {{ new Date(order.created_at).toLocaleDateString() }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                        <button v-if="order.status === 'pending'"
                            @click="acceptOrder(order)"
                            :class="{
                            'text-green-600 hover:text-green-900': order.status === 'pending',
                            'text-blue-600 hover:text-blue-900': order.status === 'Processing',
                            'text-gray-600 hover:text-gray-900': order.status === 'Shipped',
                            }"
                            class="mr-2"
                        >
                            {{ getButtonLabel(order.status) }}
                        </button>
                        <button v-if="order.status === 'Processing'"
                            @click="shipOrder(order)"
                            :class="{
                            'text-green-600 hover:text-green-900': order.status === 'pending',
                            'text-blue-600 hover:text-blue-900': order.status === 'Processing',
                            'text-gray-600 hover:text-gray-900': order.status === 'Shipped',
                            }"
                            class="mr-2"
                        >
                            {{ getButtonLabel(order.status) }}
                        </button>
                        <button v-if="order.status === 'Shipped'"
                            @click="completeOrder(order)"
                            :class="{
                            'text-green-600 hover:text-green-900': order.status === 'pending',
                            'text-blue-600 hover:text-blue-900': order.status === 'Processing',
                            'text-gray-600 hover:text-gray-900': order.status === 'Shipped',
                            }"
                            class="mr-2"
                        >
                            {{ getButtonLabel(order.status) }}
                        </button>
                      <!-- <button @click="openEditModal(order)" class="text-blue-600 hover:text-blue-900 mr-2">
                        Edit
                      </button> -->
                      <button @click="deleteOrder(order)" class="text-red-600 hover:text-red-900">
                        Delete
                      </button>
                    </td>
                  </tr>
                  <tr v-if="expandedOrders[order.id]">
                    <td colspan="7" class="px-4 py-3">
                      <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Ordered Products:</h4>
                        <ul>
                          <li v-for="product in props.details.filter(product => product.transaction_id === order.id)" 
                            :key="product.id" 
                            class="mb-2">
                            <span class="font-medium">Product name: {{ product.name }}</span><br>Quantity: <span class="font-bold">{{ product.quantity }}</span><br>Total Price: ₱{{ product.total_price }}
                            <br>=================================================================================================================
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
  
        <!-- Pagination -->
        <div class="mt-4 flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button @click="currentPage--" :disabled="currentPage === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Previous
            </button>
            <button @click="currentPage++" :disabled="currentPage === totalPages"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                to
                <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, sortedOrders.length) }}</span>
                of
                <span class="font-medium">{{ sortedOrders.length }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button @click="currentPage--" :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">Previous</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
                <button v-for="page in totalPages" :key="page" @click="currentPage = page" :class="[
                  currentPage === page ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]">
                  {{ page }}
                </button>
                <button @click="currentPage++" :disabled="currentPage === totalPages"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">Next</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 px-4 py-3 bg-gray-100 border-b">
          Completed Orders
        </h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Order ID
                </th>
                <th
                  scope="col"
                  class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Customer
                </th>
                <th
                  scope="col"
                  class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Total
                </th>
                <th
                  scope="col"
                  class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Date
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="order in props.orders.filter(order => order.status === 'Completed')"
                :key="order.id"
                class="hover:bg-gray-50 transition duration-150 ease-in-out"
              >
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ order.id }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                  {{ order.customer_name }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                  ₱{{ parseFloat(order.total_amount).toFixed(2) }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                  {{ new Date(order.created_at).toLocaleDateString() }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </AdminAuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/inertia-vue3';
  import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
  
  const props = defineProps({
    orders: {
      type: Array,
      default: () => []
    },
    details: {
      type: Array,
      default: () => []
    }
  });

  console.log(props.orders);
  console.log(props.details);
  
  const orders = ref(props.orders || []);
  const details = ref(props.details || []);
  const searchQuery = ref('');
  const currentPage = ref(1);
  const itemsPerPage = 10;
  const sortBy = ref('created_at');
  const sortDesc = ref(true);
  const expandedOrders = ref({});
  
  const filteredOrders = computed(() => {
    if (!orders.value) return [];
    return orders.value.filter(order =>
      order.customer_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      order.id.toString().includes(searchQuery.value)
    );
  });
  
  const sortedOrders = computed(() => {
    return [...filteredOrders.value].sort((a, b) => {
      let modifier = sortDesc.value ? 1 : -1;
      if (a[sortBy.value] < b[sortBy.value]) return -1 * modifier;
      if (a[sortBy.value] > b[sortBy.value]) return 1 * modifier;
      return 0;
    });
  });
  
  const paginatedOrders = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    return sortedOrders.value.slice(startIndex, startIndex + itemsPerPage);
  });
  
  const totalPages = computed(() => Math.ceil(sortedOrders.value.length / itemsPerPage));
  
  const toggleSort = (column) => {
    if (sortBy.value === column) {
      sortDesc.value = !sortDesc.value;
    } else {
      sortBy.value = column;
      sortDesc.value = true;
    }
  };
  
  const statusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'completed': return 'bg-green-100 text-green-800';
      case 'processing': return 'bg-blue-100 text-blue-800';
      case 'pending': return 'bg-yellow-100 text-yellow-800';
      case 'accepted': return 'bg-green-100 text-green-800';
      case 'shipped': return 'bg-red-100 text-red-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };
  
  const totalRevenue = computed(() => {
    return filteredOrders.value.reduce((sum, order) => sum + parseFloat(order.total_amount), 0);
  });
  
  const averageOrderValue = computed(() => {
    return filteredOrders.value.length > 0 ? (totalRevenue.value / filteredOrders.value.length) : 0;
  });

  const deleteOrder = (order) => {
    form.delete(route('orders.delete', order.id), {
        onSuccess: () => {
        console.log(`Order ${order.id} deleted successfully.`);
        form.reset();
        // Optionally reload the page or fetch updated data
        window.location.href = '/Admin/OrderManagement';
        },
        onError: (errors) => {
        console.error('Failed to delete order:', errors);
        },
    });
    };
  
  const form = useForm({
    id: null, // Order ID
  });
  
  const acceptOrder = (order) => {
    form.id = order.id; // Assign the order ID to the form
  
    form.post(route('order.accept'), {
      onSuccess: () => {
        console.log(`Order ${order.id} accepted successfully.`);
        // Redirect to another route after successful submission
        form.reset();
        // form.visit(route('order.details', { id: order.id }));
        window.location.href = '/Admin/OrderManagement';
      },
      onError: (errors) => {
        console.error('Failed to accept order:', errors);
      },
    });
    
  };

  const shipOrder = (order) => {
    form.id = order.id; // Assign the order ID to the form
  
    form.post(route('order.ship'), {
      onSuccess: () => {
        console.log(`Order ${order.id} accepted successfully.`);
        // Redirect to another route after successful submission
        form.reset();
        window.location.href = '/Admin/OrderManagement';
        // form.visit(route('order.details', { id: order.id }));
      },
      onError: (errors) => {
        console.error('Failed to accept order:', errors);
      },
    });
    
  };

  const completeOrder = (order) => {
    form.id = order.id; // Assign the order ID to the form
  
    form.post(route('order.complete'), {
      onSuccess: () => {
        console.log(`Order ${order.id} accepted successfully.`);
        // Redirect to another route after successful submission
        form.reset();
        // form.visit(route('order.details', { id: order.id }));
        window.location.href = '/Admin/OrderManagement';
      },
      onError: (errors) => {
        console.error('Failed to accept order:', errors);
      },
    });
  };
  
  const toggleOrderDetails = (orderId) => {
    expandedOrders.value[orderId] = !expandedOrders.value[orderId];
  };

  const getButtonLabel = (status) => {
    if (status === 'pending') return 'Accept';
    if (status === 'Processing') return 'Ship';
    if (status === 'Shipped') return 'Complete';
    if (status === 'Completed') return 'Done';
    return '';
    };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
  
  body {
    font-family: 'Inter', sans-serif;
  }
  
  .transition {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-duration: 150ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }
  </style>