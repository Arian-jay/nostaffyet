<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Redirect;

class AdminController extends Controller
{
    public function viewMenu()
    {
        // Fetch data from the `products` table and group it by category
        $menuItems = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category')
            ->get()
            ->groupBy('category')
            ->toArray();

        return Inertia::render('Admin/ManageMenu', [
            'menuItems' => $menuItems,
            'success' => session('success'),
        ]);
    }

    public function updateMenu(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'availability' => 'required|boolean',
        ]);

        $affectedRows = DB::table('products')
            ->where('id', $id)
            ->update([
                'price' => $request->price,
                'qty' => $request->qty,
                'availability' => $request->availability,
                'updated_by' => auth()->id(),
            ]);

        if ($affectedRows) {
            return redirect()->back()->with('success', 'Menu item updated successfully.');
        }

        return redirect()->back()->withErrors('Failed to update menu item.');
    }

    public function deleteMenuProduct($id)
    {
        try {
            $deleted = DB::table('products')
                ->where('id', $id)
                ->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'Menu item deleted successfully.');
            }

            return redirect()->back()->withErrors('Failed to delete menu item.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    public function viewAccountManagement()
    {
        return Inertia::render('Admin/AccountManagement');
    }

    public function insertIntoAccountManagement(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users', // Phone validation
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:role,id', // Validate role_id against the roles table
        ]);

        // Insert the new user into the users table
        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],  // Directly use the validated role_id
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return a success message
        return back()->with('message', 'Account created successfully!');
    }

    public function displayUsers()
    {
        // Use DB queries to fetch users grouped by role_id
        $users = [
            'admin' => DB::table('users')->where('role_id', 1)->get(),
            'staff' => DB::table('users')->where('role_id', 2)->get(),
            'customer' => DB::table('users')->where('role_id', 3)->get(),
        ];

        return response()->json($users);
    }

    public function updateUsers(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Ensure the current user's email is not considered unique
            'phone' => 'required|string|max:15|unique:users,phone,' . $id, // Ensure current phone is not considered unique
            'role_id' => 'required|exists:role,id', // Validate role_id exists in roles table
        ]);

        // Update the user data using raw DB query
        DB::table('users')->where('id', $id)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role_id' => $validated['role_id'], // Use role_id directly
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Account updated successfully!']);
    }

    public function destroyUsers($id)
    {
        // Delete the user using a DB query
        DB::table('users')->where('id', $id)->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function viewProductManagement()
    {
        return Inertia::render('Admin/ProductManagement');
    }

    public function displayProducts()
    {
        $products = DB::table('products')->get();
        $categories = DB::table('categories')->pluck('name', 'id');
        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function insertProducts(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'qty' => 'required|integer|min:0',
                'category_id' => 'required|integer|exists:categories,id',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'availability' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $imagePath = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

        DB::table('products')->insert([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'qty' => $validated['qty'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'availability' => $validated['availability'],
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('Admin/ProductManagement')->with('success', 'Add Product Successfully');
    }

   

public function updateProducts(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'availability' => 'required|boolean',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }

    // Check if the product exists
    $product = DB::table('products')->where('id', $id)->first();
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Handle image upload
    $imagePath = $product->image;
    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $imagePath = $request->file('image')->store('images', 'public');
    }


    // Update the product
    DB::table('products')->where('id', $id)->update([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'qty' => $validated['qty'],
        'category_id' => $validated['category_id'],
        'description' => $validated['description'],
        'image' => $imagePath,
        'availability' => $validated['availability'],
        'updated_by' => auth()->id(),
        'updated_at' => now(),
    ]);

    return redirect('Admin/ProductManagement')->with('success', 'Product updated successfully');
}




    public function deleteProduct($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        DB::table('products')->where('id', $id)->delete();

        return response()->json(['success' => 'Product deleted successfully']);
    }

    public function viewOrder()
    {

        $orders = DB::select('SELECT * FROM transaction_summary_admin');
        $details = DB::select('SELECT * FROM order_products');
        return Inertia::render('Admin/OrderManagement', [
            'orders' => $orders,
            'details' => $details,
        ]);
       // return Inertia::render('Admin/OrderManagement');
    }


    public function displayOrder()
    {
        try {
            $orders = DB::table('orders')
                ->select('orders.*', 'users.name as customer_name', 'staff.name as staff_name')
                ->join('users', 'orders.customer_id', '=', 'users.id')
                ->leftJoin('users as staff', 'orders.staff_id', '=', 'staff.id')
                ->orderBy('orders.created_at', 'desc')
                ->get();
            
            return Inertia::render('Admin/OrderManagement', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            // Log the error and return a custom message
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error fetching the orders.'], 500);
        }
    }
    
    
    public function updateOrder(Request $request, $id)
    {
        $status = $request->input('status');
        
        DB::table('transactions')
            ->where('id', $id)
            ->update(['status' => $status]);

        return Redirect::back()->with('success','Update order successfully!');
    }

    public function getOrderHistory()
    {
        $orders = DB::select('SELECT * FROM transaction_summary_admin');
        dd($order);
        return Inertia::render('Admin/OrderManagement', [
            'orders' => $orders,
        ]);
    }

    public function deleteOrder($id)
    {
        // Check if the order exists
        $order = DB::table('transactions')->where('id', $id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Delete the order
        DB::table('transactions')->where('id', $id)->delete();

        return Inertia::location(route('ordermanagement'));

        // Return a success response
    }


    public function acceptOrder(Request $request){
        
        $validated = $request->validate([
            'id' => 'required|numeric'
        ]);

        $items = DB::table('transactions')
            ->where('id', $validated['id'])
            ->update(['status' => 'Processing']);

        return Inertia::location('/Admin/OrderManagement');
    }

    public function shipOrder(Request $request){
        
        $validated = $request->validate([
            'id' => 'required|numeric'
        ]);

        $items = DB::table('transactions')
            ->where('id', $validated['id'])
            ->update(['status' => 'Shipped']);

    return Inertia::location('/Admin/OrderManagement');
    }


    public function completeOrder(Request $request){
        
        $validated = $request->validate([
            'id' => 'required|numeric'
        ]);

        $items = DB::table('transactions')
            ->where('id', $validated['id'])
            ->update(['status' => 'Completed']);

    return Inertia::location('/Admin/OrderManagement');
    }
}
