<template>
    <AdminAuthenticatedLayout>

        <Head title="Product Management" />

        <div
            class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600">
                                Product Management
                            </span>
                        </h1>
                    </div>

                    <!-- Dashboard Overview -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div v-for="(stat, index) in dashboardStats" :key="index"
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <component :is="stat.icon" class="h-6 w-6 text-gray-400" aria-hidden="true" />
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                                {{ stat.name }}
                                            </dt>
                                            <dd>
                                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                                    {{ stat.value }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                   <!-- Add Product Button -->
          <button @click="openAddModal"
            class="group mb-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 ease-in-out transform hover:scale-105 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            <PlusIcon class="h-5 w-5 mr-2 transition-transform duration-300 ease-in-out transform group-hover:rotate-180" />
            Add New Product
          </button>

                    <!-- Error Messages -->
          <transition-group name="fade" tag="div">
            <div v-for="error in errors" :key="error"
              class="rounded-md bg-red-50 dark:bg-red-900 p-4 mb-4 shadow-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <XCircleIcon class="h-5 w-5 text-red-400 dark:text-red-300" />
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800 dark:text-red-200">{{ error }}</h3>
                </div>
              </div>
            </div>
          </transition-group>

                    <!-- Success Message -->
          <transition-group name="fade" tag="div">
            <div v-if="successMessage" class="rounded-md bg-green-50 dark:bg-green-900 p-4 mb-4 shadow-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <CheckCircleIcon class="h-5 w-5 text-green-400 dark:text-green-300" />
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ successMessage }}</p>
                </div>
              </div>
            </div>
          </transition-group>

          <!-- Product Categories -->
          <div v-for="(productsByCategory, categoryName) in groupedProducts" :key="categoryName" class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
              <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm mr-3">
                {{ productsByCategory.length }}
              </span>
              {{ categoryName }}
            </h2>

            <div v-if="productsByCategory.length"
              class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
              <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                <li v-for="product in productsByCategory" :key="product.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                  <div class="px-4 py-4 sm:px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div class="flex items-center mb-2 sm:mb-0">
                      <img :src="`/storage/${product.image}`" :alt="product.name"
                        class="h-16 w-16 rounded-lg object-cover mr-4 shadow-md">
                      <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ product.name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Price: ₱ {{ product.price }}</p>
                      </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-5 sm:space-y-0 sm:space-x-20 space-x-32 mr-12">
                      <span 
                        :class="[product.availability ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200', 'px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full']">
                        {{ product.availability ? 'Available' : 'Not Available' }}
                      </span>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Stock: {{ product.qty }}</p>
                      <div class="flex space-x-5 ">
                        <button @click="openQuickEditModal(product)"
                          class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200">
                          <PencilIcon class="h-5 w-5" />
                        </button>
                        <button @click="deleteProduct(product.id)"
                          class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                          <TrashIcon class="h-5 w-5" />
                        </button>
                      </div>
                    </div>

                  </div>
                </li>
              </ul>
            </div>
            <p v-else
              class="text-gray-500 dark:text-gray-400 text-center py-8 bg-white dark:bg-gray-800 rounded-lg shadow-md">
              No products found in this category.
            </p>
          </div>
        </div>
      </div>
    </div>

        <!-- Modal Component -->
        <transition name="modal">
      <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button @click="closeModal" type="button"
                                class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Close</span>
                                <XMarkIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    {{ isEditing ? 'Edit Product' : 'Add New Product' }}
                                </h3>
                                <div class="mt-2">
                                    <form @submit.prevent="isEditing ? updateProduct() : saveProduct()">
                                        <!-- Form fields -->
                                        <div class="space-y-4">
                                            <div>
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                                <input v-model="form.name" type="text" name="name" id="name"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                            </div>

                                            <div>
                                                <label for="price"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 sm:text-sm">P</span>
                                                    </div>
                                                    <input v-model="form.price" type="number" step="0.01" name="price"
                                                        id="price"
                                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                        placeholder="0.00" required>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="qty"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                                <input v-model="form.qty" type="number" name="qty" id="qty"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                            </div>

                                            <div>
                                                <label for="category"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                                <select v-model="form.category_id" id="category" name="category"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                                    <option value="" disabled>Select a category</option>
                                                    <option v-for="(name, id) in categories" :key="id" :value="id">{{
                                                        name }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="description"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                                <textarea v-model="form.description" id="description" name="description"
                                                    rows="3"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                            </div>

                                            <div v-if="!isEditing">
                                                <label for="image"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                                                <div
                                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                                    <div class="space-y-1 text-center">
                                                        <!-- Preview Image -->
                                                        <div v-if="uploadedImage">
                                                            <img :src="uploadedImage" alt="Preview"
                                                                class="mx-auto h-24 w-24 object-cover rounded-md">
                                                            <!-- Optionally, you can add a button to remove or change the image -->
                                                            <button @click="removeImage"
                                                                class="mt-2 text-sm text-red-600 hover:underline dark:text-red-400">
                                                                Remove Image
                                                            </button>
                                                        </div>

                                                        <!-- File Upload Section -->
                                                        <div v-else>
                                                            <PhotoIcon class="mx-auto h-12 w-12 text-gray-400" />
                                                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                                <label for="file-upload"
                                                                    class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                                    <span class="ml-8">Upload a file</span>
                                                                    <input @change="handleImageUpload" id="file-upload"
                                                                        name="file-upload" type="file" class="sr-only"
                                                                        accept="image/*">
                                                                </label>
                                                            </div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG,
                                                                JPG, GIF up to 10MB</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div>
                                                <label for="availability"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Availability</label>
                                                <select v-model="form.availability" id="availability"
                                                    name="availability"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                                    <option :value="true">Available</option>
                                                    <option :value="false">Unavailable</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                            <button type="submit"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                                {{ isEditing ? 'Update' : 'Save' }}
                                            </button>
                                            <button type="button" @click="closeModal"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Quick Edit Modal -->
        <transition name="modal">
      <div v-if="showQuickEditModal" class="fixed z-10 inset-0 overflow-y-auto  border border-black" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button @click="closeQuickEditModal" type="button"
                                class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Close</span>
                                <XMarkIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full  border border-black p-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    EDIT PRODUCT
                                </h3>
                                <div class="mt-2">
                                    <form @submit.prevent="updateQuickEdit">
                                        <div class="space-y-4">
                                            <!-- Name -->
                                            <div>
                                                <label for="quick-edit-name"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                                <input v-model="quickEditForm.name" type="text" id="quick-edit-name"
                                                    name="quick-edit-name"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required />
                                            </div>

                                            <!-- Price -->
                                            <div>
                                                <label for="quick-edit-price"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                                    </div>
                                                    <input v-model="quickEditForm.price" type="number" step="0.01"
                                                        id="quick-edit-price" name="quick-edit-price"
                                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                        required />
                                                </div>
                                            </div>

                                            <!-- Quantity -->
                                            <div>
                                                <label for="quick-edit-qty"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                                <input v-model="quickEditForm.qty" type="number" id="quick-edit-qty"
                                                    name="quick-edit-qty"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required />
                                            </div>

                                            <!-- Category -->
                                            <div>
                                                <label for="quick-edit-category"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                                <select v-model="quickEditForm.category_id" id="quick-edit-category"
                                                    name="quick-edit-category"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                                    <option value="" disabled>Select a Category</option>
                                                    <option v-for="(name, id) in categories" :key="id" :value="id">{{
                                                        name }}</option>
                                                </select>
                                            </div>

                                            <!-- Description -->
                                            <div>
                                                <label for="quick-edit-description"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                                <textarea v-model="quickEditForm.description"
                                                    id="quick-edit-description" name="quick-edit-description" rows="3"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                            </div>

                                            <!-- Availability -->
                                            <div>
                                                <label for="quick-edit-availability"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Availability</label>
                                                <select v-model="quickEditForm.availability"
                                                    id="quick-edit-availability" name="quick-edit-availability"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                                    <option :value="true">Available</option>
                                                    <option :value="false">Unavailable</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div
                                            class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                            <button type="submit"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                                Update
                                            </button>
                                            <button type="button" @click="closeQuickEditModal"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

    </AdminAuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Head } from '@inertiajs/inertia-vue3';
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import {
    CubeIcon,
    CurrencyDollarIcon,
    ShoppingCartIcon,
    TagIcon,
    PlusIcon,
    XCircleIcon,
    CheckCircleIcon,
    PencilIcon,
    TrashIcon,
    XMarkIcon,
    PhotoIcon
} from '@heroicons/vue/24/outline';




const products = ref([]);
const categories = ref({});
const showModal = ref(false);
const showQuickEditModal = ref(false);
const isEditing = ref(false);
const errors = ref([]);
const successMessage = ref('');
const isDarkMode = ref(false);
const uploadedImage = ref(null);

const removeImage = () => {
    if (uploadedImage.value) {
        URL.revokeObjectURL(uploadedImage.value); // Free memory
        uploadedImage.value = null; // Reset the preview
    }
};




const categoryOrder = [
    "Holiday Limited Edition",
    "Coffee Beverage",
    "Foods & Snacks",
    "Coffee Beans & Ground Coffee"
];

const form = useForm({
    id: null,
    name: '',
    price: '',
    qty: '',
    category_id: '',
    description: '',
    image: null,
    availability: true,
    //updated_by: null,
});

const quickEditForm = useForm({
    id: null,
    name: '',
    price: '',
    qty: '',
    category_id: '',
    description: '',
    image: null,
    availability: true,
    //updated_by: null,
});

const dashboardStats = computed(() => [
    { name: 'Total Products', value: products.value.length, icon: CubeIcon },
    { name: 'Total Categories', value: Object.keys(categories.value).length, icon: TagIcon },
    { name: 'Available Products', value: products.value.filter(p => p.availability).length, icon: ShoppingCartIcon },
    { name: 'Total Value', value: `₱ ${products.value.reduce((sum, p) => sum + p.price * p.qty, 0).toFixed(2)}`, icon: CurrencyDollarIcon },
]);

const groupedProducts = computed(() => {
    const grouped = {};
    products.value.forEach(product => {
        const category = categories.value[product.category_id];
        if (category) {
            if (!grouped[category]) {
                grouped[category] = [];
            }
            grouped[category].push(product);
        }
    });
    const sortedCategories = categoryOrder.filter(categoryName => grouped[categoryName]);
    const orderedGroupedProducts = {};
    sortedCategories.forEach(categoryName => {
        orderedGroupedProducts[categoryName] = grouped[categoryName];
    });
    return orderedGroupedProducts;
});

const fetchProducts = async () => {
    try {
        const response = await axios.get(route('products.index'));
        console.log('API response:', response.data);
        products.value = response.data.products;
        categories.value = response.data.categories;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

const openAddModal = () => {
    form.reset();
    form.availability = true;
    isEditing.value = false;
    errors.value = [];
    showModal.value = true;
};

const openQuickEditModal = (product) => {
    quickEditForm.reset();
    quickEditForm.id = product.id;
    quickEditForm.name = product.name;
    quickEditForm.price = product.price;
    quickEditForm.qty = product.qty;
    quickEditForm.category_id = product.category_id;
    quickEditForm.description = product.description;
    quickEditForm.availability = product.availability;
    showQuickEditModal.value = true;
};

const saveProduct = () => {
    form.post(route('products.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            successMessage.value = 'Product added successfully!';
            fetchProducts();
            closeModal();
        },
        onError: (errors) => {
            console.error(errors);
            this.errors = Object.values(errors).flat();
        },
    });
};

const updateProduct = () => {
    form.put(route('products.update', form.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            successMessage.value = 'Product updated successfully!';
            fetchProducts();
            closeModal();
        },
        onError: (errors) => {
            console.error(errors);
            this.errors = Object.values(errors).flat();
        },
    });
};

const updateQuickEdit = () => {
    quickEditForm.put(route('products.update', quickEditForm.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            successMessage.value = 'Product updated successfully!';
            fetchProducts();
            closeQuickEditModal();
        },
        onError: (errors) => {
            console.error(errors);
            this.errors = Object.values(errors).flat();
        },
    });
};

const deleteProduct = async (id) => {
    if (!confirm('Are you sure you want to delete this product?')) return;

    try {
        await axios.delete(route('products.destroy', id));
        successMessage.value = 'Product deleted successfully!';
        fetchProducts();
    } catch (error) {
        console.error(error);
        errors.value = ['An error occurred while deleting the product.'];
    }
};

const closeModal = () => {
    showModal.value = false;
    errors.value = [];
};

const closeQuickEditModal = () => {
    showQuickEditModal.value = false;
    errors.value = [];
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        uploadedImage.value = URL.createObjectURL(file);
    }
};


onMounted(() => {
    fetchProducts();
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.classList.toggle('dark', isDarkMode.value);
});
</script>

<style scoped>
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
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .modal-container,
.modal-leave-active .modal-container {
    transition: all 0.3s ease-in-out;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    transform: scale(1.1);
}

@media (prefers-color-scheme: dark) {
    body {
        background-color: #1a202c;
        color: #e2e8f0;
    }
}
</style>