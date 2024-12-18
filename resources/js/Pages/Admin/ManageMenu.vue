<template>

    <Head title="Admin Menu" />

    <AdminAuthenticatedLayout>


        <div class="container mx-auto px-10 py-8">
            <div class="mb-10 border">
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    INVENTORY
                </h1>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                {{ successMessage }}
            </div>

            <!-- Menu Items by Category -->
            <div v-for="(items, category) in sortedMenuItems" :key="category" class="mb-10 container">
                <h2 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2">{{ category }}</h2>

                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qty</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Availability</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in items" :key="item.id"
                                class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.name
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">P {{ item.price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.qty }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        :class="{ 'text-green-600 bg-green-100': item.availability === true, 'text-red-600 bg-red-100': item.availability === false }"
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ item.availability === true ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="editMenu(item.id)"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md transition-colors duration-200">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Menu Modal -->
            <div v-if="showEditModal"
                class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-75 z-50 transition-opacity duration-300 ease-in-out">
                <div class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 ease-in-out"
                    :class="{ 'scale-95 opacity-0': !showEditModal, 'scale-100 opacity-100': showEditModal }">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-gray-800">Edit Menu Item</h3>
                        <button @click="cancelEdit"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="updateMenu" class="px-6 py-4 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="text-gray-900 bg-gray-100 rounded-md p-3 font-semibold">{{ editForm.name }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <p class="text-gray-900 bg-gray-100 rounded-md p-3 font-semibold">{{ editForm.category }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">P</span>
                                </div>
                                <input v-model="editForm.price" type="number" id="price" step="0.01"
                                    class="block w-full pl-7 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="0.00" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="qty" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input v-model="editForm.qty" type="number" id="qty"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter quantity" />
                        </div>
                        
                        <div class="space-y-2">
                            <label for="availability"
                                class="block text-sm font-medium text-gray-700">Availability</label>
                            <div class="relative">
                                <select v-model="editForm.availability" id="availability"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none">
                                    <option :value="true">Available</option>
                                    <option :value="false">Unavailable</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" @click="cancelEdit"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';

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
    availability: 'Available',
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
        const orderA = categoryOrderMap[categoryA] || categoryOrderMap.DEFAULT;
        const orderB = categoryOrderMap[categoryB] || categoryOrderMap.DEFAULT;
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
    editForm.put(route('menu.update', editForm.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            successMessage.value = 'Menu item updated successfully.';
        },
        onError: () => {
            successMessage.value = ''; // Reset success message
            alert('Error updating menu item. Please try again.');
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