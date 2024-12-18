<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const orders = ref([]);

const fetchOrderHistory = async () => {
  try {
    const response = await axios.get(route('orders.history'));
    orders.value = response.data;
  } catch (error) {
    console.error('Error fetching order history:', error);
  }
};

onMounted(fetchOrderHistory);
</script>

<template>
  <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg leading-6 font-medium text-gray-900">Order History</h3>
    </div>
    <div class="border-t border-gray-200">
      <ul role="list" class="divide-y divide-gray-200">
        <li v-for="order in orders" :key="order.id" class="px-4 py-4 sm:px-6">
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-indigo-600 truncate">
              Order #{{ order.id }}
            </p>
            <div class="ml-2 flex-shrink-0 flex">
              <p :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                order.status.toLowerCase() === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
              ]">
                {{ order.status }}
              </p>
            </div>
          </div>
          <div class="mt-2 sm:flex sm:justify-between">
            <div class="sm:flex">
              <p class="flex items-center text-sm text-gray-500">
                Total: ${{ order.total}}
              </p>
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
              <p>
                {{ new Date(order.created_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>