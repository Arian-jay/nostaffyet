<script setup>
import { reactive, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    phone: '',
    role_id: 1,
});
// Success message state
const successMessage = ref('');

// Store users fetched from the backend
const users = reactive({
    admin: [],
    staff: [],
    customer: [],
});

// Modal state
const showEditModal = ref(false);
const editForm = useForm({
    id: null,
    name: '',
    email: '',
    phone: '',
    role: '',
});

// Modal state for delete confirmation
const showDeleteModal = ref(false);
const deleteUserId = ref(null);  // Store the user ID to be deleted

// Function to create an account
const createAccount = () => {
    console.log(form);  // Debugging log to check form data
    if (form.password !== form.confirmPassword) {
        alert('Passwords do not match');
        return;
    }

    form.post(route('account.store'), {
        onSuccess: () => {
            form.reset();
            successMessage.value = 'Account created successfully!';
            fetchUsers(); // Refresh users after creating a new account
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};




// Fetch users grouped by role
const fetchUsers = async () => {
    try {
        const response = await axios.get(route('users.grouped'));
        console.log(response.data); // Log the response to check its structure
        users.admin = response.data.admin || [];
        users.staff = response.data.staff || [];
        users.customer = response.data.customer || [];
    } catch (error) {
        console.error("Error fetching users:", error);
    }
};

// Fetch users on component mount
fetchUsers();

// Open Edit Modal
const openEditModal = (user) => {
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.phone = user.phone;
    editForm.role_id = user.role_id;  // Ensure role_id is also updated
    showEditModal.value = true;
};


// Update User
// Update User
const updateUser = () => {
    // Validate if data exists
    if (!editForm.name || !editForm.email || !editForm.phone || !editForm.role_id) {
        alert('Please fill all fields');
        return;
    }

    // Update user
    axios.put(route('account.update', editForm.id), editForm)
        .then(() => {
            successMessage.value = 'Account updated successfully!';
            showEditModal.value = false;
            fetchUsers();  // Refresh users list
        })
        .catch((error) => {
            console.error('Error updating user:', error);
            alert('There was an error updating the account.');
        });
};






// Close Edit Modal
const cancelEdit = () => {
    showEditModal.value = false;
    editForm.reset();
};

// Open Delete Modal and set user ID
const confirmDeleteUser = (userId) => {
    deleteUserId.value = userId;  // Store the ID of the user to delete
    showDeleteModal.value = true;  // Show the delete confirmation modal
};

// Function to delete the user
const deleteUser = () => {
    axios.delete(route('account.destroy', deleteUserId.value))
        .then(() => {
            successMessage.value = 'User deleted successfully!';  // Show success message
            showDeleteModal.value = false;  // Close the modal
            fetchUsers();  // Refresh the users list
        })
        .catch((error) => {
            console.error(error);
            alert('Error deleting user');
        });
};
</script>

<template>

    <Head title="Account Management" />

    <AdminAuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div v-if="successMessage"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                {{ successMessage }}
            </div>

            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Account Management</h1>

            <!-- Display Users by Role -->
            <div class="grid grid-cols-1 gap-6 mb-10">
                <div v-for="(roleUsers, role) in users" :key="role"
                    class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 capitalize">{{ role }}s</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No.</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(user, index) in roleUsers" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="openEditModal(user)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-2">Update</button>
                                    <button @click="confirmDeleteUser(user.id)"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Account Creation Form -->
            <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create an Account</h2>
                <form @submit.prevent="createAccount" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" type="text" v-model="form.name"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" v-model="form.email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required />
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input id="phone" type="tel" v-model="form.phone"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" type="password" v-model="form.password"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required />
                    </div>

                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input id="confirmPassword" type="password" v-model="form.confirmPassword"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required />
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" v-model="form.role_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="2">Staff</option>
                            <option value="1">Admin</option>
                            <option value="3">Customer</option>
                        </select>

                    </div>

                    <PrimaryButton type="submit"
                        class=" hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Create Account
                    </PrimaryButton>
                </form>
            </div>

            <!-- Edit User Modal -->
            <div v-if="showEditModal"
                class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-75 z-50 p-4">
                <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-gray-800">Edit Account</h3>
                        <button @click="cancelEdit" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal with form inside -->
                    <form @submit.prevent="updateUser" v-if="showEditModal" class="px-6 py-4 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="editForm.name" type="text"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="editForm.email" type="email"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input v-model="editForm.phone" type="tel"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select v-model="editForm.role_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1">Admin</option>
                                <option value="2">Staff</option>
                                <option value="3">Customer</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button @click="cancelEdit" type="button"
                                class="px-4 py-2 text-gray-500 border rounded-md hover:bg-gray-100 transition duration-150 ease-in-out">Cancel</button>
                            <PrimaryButton type="submit"
                                class=" hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                Update User
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal"
                class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-75 z-50 p-4">
                <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-gray-800">Confirm Deletion</h3>
                        <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4 text-gray-700">
                        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                    </div>
                    <div class="flex justify-end space-x-3 p-4">
                        <button @click="showDeleteModal = false"
                            class="px-4 py-2 text-gray-500 border rounded-md hover:bg-gray-100 transition duration-150 ease-in-out">Cancel</button>
                        <PrimaryButton @click="deleteUser" class="bg-red-600 hover:bg-red-700">Delete</PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>

<style scoped>
/* Styles for modal */
.fixed {
    z-index: 50;
}
</style>
