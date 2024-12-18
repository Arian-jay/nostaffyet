<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use function Laravel\Prompts\select;


class StaffController extends Controller
{
    public function displayMenu(){
        // Fetch data from the `products` table and group it by category
        $menuItems = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category')
            ->get()
            ->groupBy('category')
            ->toArray();

        return Inertia::render('Staff/StaffMenuManage', [
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
            'updated_by' => auth()->id(), // Corrected here
        ]);

    if ($affectedRows) {
        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }

    return redirect()->back()->withErrors('Failed to update menu item.');
}


public function viewOrder()
{
    // Query the 'order_summary' view to fetch summarized order data
    $orders = DB::table('order_summary')->get('*');

    // Transform the data for the frontend (optional for cleaner output)
    $orders = $orders->map(function ($order) {
        return [
            'id' => $order->order_id,
            'customer_name' => $order->customer_name,
            'total_amount' => $order->total, // Alias from the view
            'status' => $order->status,
            'created_at' => $order->order_date,
            'updated_at' => null, // 'updated_at' is not included in the view
            'order_details' => [], // Empty details, as the view does not include order details
        ];
    });

    // Return the transformed data to the Inertia view
    return Inertia::render('Staff/StaffOrderManage', [
        'orders' => $orders
    ]);
}


    public function acceptOrder(Order $order)
    {
        if ($order->status === 'Pending') {
            $order->update(['status' => 'Processing']);
            return redirect()->back()->with('success', 'Order accepted successfully.');
        }
        return redirect()->back()->with('error', 'Only pending orders can be accepted.');
    }

    public function updateOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Completed',
        ]);

        $order->update($validated);
        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    public function destroyOrder(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }
}



