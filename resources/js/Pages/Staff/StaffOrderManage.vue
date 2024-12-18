<template>

    <Head title="Order Management" />

    <StaffAuthenticatedLayout>
        <div class="p-6 bg-gradient-to-br from-indigo-50 to-white shadow-lg rounded-lg">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Order Management</h2>

            <!-- Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div v-for="stat in summaryStats" :key="stat.title" class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ stat.title }}</h3>
                    <p :class="['text-2xl font-bold', stat.colorClass]">{{ stat.value }}</p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-4">
                <div class="relative">
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
                                <th v-for="header in tableHeaders" :key="header" scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="header !== 'Actions' && toggleSort(header.toLowerCase())">
                                    {{ header }}
                                    <span v-if="sortBy === header.toLowerCase() && header !== 'Actions'" class="ml-1">
                                        <svg v-if="!sortDesc" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
  <tr 
    v-for="order in paginatedOrders" 
    :key="order.id" 
    class="hover:bg-gray-50 transition duration-150 ease-in-out"
  >
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.id }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.customer_name }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ parseFloat(order.total_amount).toFixed(2) }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
      <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', statusColor(order.status)]">
        {{ order.status }}
      </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ new Date(order.created_at).toLocaleDateString() }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.payment_method }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
      <button 
        @click="openAcceptModal(order)" 
        class="text-green-600 hover:text-green-900 mr-2" 
        :disabled="order.status !== 'Pending'">
        Accept
      </button>
      <button @click="openEditModal(order)" class="text-blue-600 hover:text-blue-900 mr-2">Edit</button>
      <button @click="openDeleteModal(order)" class="text-red-600 hover:text-red-900">Delete</button>
    </td>
  </tr>
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
                            <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, sortedOrders.length)
                                }}</span>
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
                                currentPage === page
                                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
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

        <!-- Accept Modal -->
        <Modal v-if="showAcceptModal" @close="closeModals">
            <template #title>Accept Order</template>
            <template #content>
                <p class="text-sm text-gray-500">
                    Are you sure you want to accept this order?
                </p>
            </template>
            <template #footer>
                <Button @click="acceptOrder" variant="success">Accept</Button>
                <Button @click="closeModals" variant="secondary">Cancel</Button>
            </template>
        </Modal>

        <!-- Edit Modal -->
        <Modal v-if="showEditModal" @close="closeModals">
            <template #title>Edit Order</template>
            <template #content>
                <form @submit.prevent="updateOrder">
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                        <select v-model="form.status" id="status"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </form>
            </template>
            <template #footer>
                <Button @click="updateOrder" variant="primary">Save Changes</Button>
                <Button @click="closeModals" variant="secondary">Cancel</Button>
            </template>
        </Modal>

        <!-- Delete Modal -->
        <Modal v-if="showDeleteModal" @close="closeModals">
            <template #title>Delete Order</template>
            <template #content>
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this order? This action cannot be undone.
                </p>
            </template>
            <template #footer>
                <Button @click="deleteOrder" variant="danger">Delete</Button>
                <Button @click="closeModals" variant="secondary">Cancel</Button>
            </template>
        </Modal>
    </StaffAuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import StaffAuthenticatedLayout from '@/Layouts/StaffAuthenticatedLayout.vue'
import Modal from '@/Components/Modal.vue'

const page = usePage()
const orders = ref(page.props.orders || [])
const searchQuery = ref('')
const sortBy = ref('id')
const sortDesc = ref(false)
const currentPage = ref(1)
const itemsPerPage = 10
const showAcceptModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const selectedOrder = ref(null)
const form = ref({
    status: '',
})

const filteredOrders = computed(() => {
    return orders.value.filter(
        (order) =>
            order.id.toString().includes(searchQuery.value) ||
            order.customer_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const sortedOrders = computed(() => {
    return [...filteredOrders.value].sort((a, b) => {
        let modifier = sortDesc.value ? -1 : 1
        if (a[sortBy.value] < b[sortBy.value]) return -1 * modifier
        if (a[sortBy.value] > b[sortBy.value]) return 1 * modifier
        return 0
    })
})

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return sortedOrders.value.slice(start, end)
})

const totalPages = computed(() => Math.ceil(sortedOrders.value.length / itemsPerPage))

const summaryStats = computed(() => [
    { title: 'Total Orders', title: 'Total Orders', value: filteredOrders.value.length, colorClass: 'text-indigo-600' },
    { title: 'Total Revenue', value: `$${calculateTotalRevenue()}`, colorClass: 'text-green-600' },
    { title: 'Average Order Value', value: `$${calculateAverageOrderValue()}`, colorClass: 'text-blue-600' }
]);

const tableHeaders = [
  'Order ID', 
  'Customer', 
  'Total', 
  'Status',
  'Date', 
  'Payment Method',
  'Actions'
];


const toggleSort = (header) => {
    if (sortBy.value === header) {
        sortDesc.value = !sortDesc.value
    } else {
        sortBy.value = header
        sortDesc.value = false
    }
}

const statusColor = (status) => {
    switch (status.toLowerCase()) {
        case 'completed': return 'bg-green-100 text-green-800'
        case 'processing': return 'bg-yellow-100 text-yellow-800'
        case 'pending': return 'bg-red-100 text-red-800'
        case 'shipped': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const openAcceptModal = (order) => {
    selectedOrder.value = order
    showAcceptModal.value = true
}

const openEditModal = (order) => {
    selectedOrder.value = { ...order }
    form.value.status = order.status
    showEditModal.value = true
}

const openDeleteModal = (order) => {
    selectedOrder.value = order
    showDeleteModal.value = true
}

const closeModals = () => {
    showAcceptModal.value = false
    showEditModal.value = false
    showDeleteModal.value = false
    selectedOrder.value = null
    form.value.status = ''
}

const acceptOrder = () => {
    if (selectedOrder.value && selectedOrder.value.status === 'Pending') {
        Inertia.post(route('staff.orders.accept', selectedOrder.value.id), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeModals()
                // Update the order status in the local state
                const index = orders.value.findIndex(o => o.id === selectedOrder.value.id)
                if (index !== -1) {
                    orders.value[index].status = 'Processing'
                }
            }
        })
    }
}


const updateOrder = () => {
    Inertia.put(route('staff.orders.update', selectedOrder.value.id), form.value, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeModals()
            // Update the order status in the local state
            const index = orders.value.findIndex(o => o.id === selectedOrder.value.id)
            if (index !== -1) {
                orders.value[index].status = form.value.status
            }
        }
    })
}

const deleteOrder = () => {
    Inertia.delete(route('staff.orders.destroy', selectedOrder.value.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeModals()
            // Remove the order from the local state
            orders.value = orders.value.filter(o => o.id !== selectedOrder.value.id)
        }
    })
}

const calculateTotalRevenue = () => {
    return filteredOrders.value.reduce((sum, order) => sum + parseFloat(order.total_amount), 0).toFixed(2)
}

const calculateAverageOrderValue = () => {
    const total = calculateTotalRevenue()
    return (total / filteredOrders.value.length || 0).toFixed(2)
}

onMounted(() => {
    // Any additional setup if needed
})
</script>

<style scoped>
/* Add any component-specific styles here */
</style>