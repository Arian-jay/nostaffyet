<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Category;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    
    //kini nga function ang mag kuha sa values sa products ug categories para ihatag didto sa CustomerDashboard para e display ang products gamit inertia.
    public function customerDashboardDisplay()
    {
        // Retrieve all products
        $prod = Product::all();
        $cat = category::all();
        // Return Inertia response, passing data to the Vue component
        return Inertia::render('Customer/CustomerDashboard', [
            'prod' => $prod,
            'cat' => $cat,
        ]);
    }

    //kani nga function ang mag kuha sa product ug categories para ipasa didto sa Menu nga vue para ipang display ang products
    public function menuDisplay()
    {
        // Retrieve all products
        $products = Product::all();
        $category = Category::all();
        // Return Inertia response, passing data to the Vue component
        return Inertia::render('Customer/Menu', [
            'products' => $products,
            'category' => $category,
        ]);
    }


    //kini nga function ang ma gamit kung mag search ka ug product didto sa imong menu page
    public function search(Request $request){
        $query = $request->input('q');
        $products = Product::where('name', 'like', '%' . $query . '%')->get();

        return response()->json($products);
    }


    //mao ni siya ang function para ma add sa cart ang product nga iyang gi pislit
    public function addToCart(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $validated['id'];
        $quantity = $validated['qty'];

        // Check if the item already exists in the cart
        $cart = DB::table('carts')
            ->where('user_id', $userId)
            ->first();

        if ($cart) {
            
            $item = DB::table('cart_items')
                ->where('product_id', $productId)
                ->where('cart_id', $cart->id)
                ->first();

            
            if ($item) {
                // Update the quantity if the item exists
                DB::table('cart_items')
                    ->where('product_id', $productId)
                    ->where('cart_id', $cart->id)
                    ->update(['quantity' => $item->quantity + $quantity]);
            } else {
                // Insert a new cart item if the product is not in the cart
                DB::table('cart_items')->insert([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
            
            
            // Update the quantity if the item exists
            
        } else {
            // Insert a new item into the cart
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
            ]);

            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('menu');
    }

    //kani nga function kay nag gamit ug view didto sa db nya gikuha diri para e display saiyang cart nga page
    public function getCartsWithProducts()
    {
        $cartsWithProducts = DB::table('cart_view')
            ->where('user_id', Auth::id())
            ->get();
        return Inertia::render('Customer/Cart', [
            'cart' => $cartsWithProducts,
        ]);
    }


    //kani nga function kay mao ni siya ang mag delete sa imong product sa cart if ever di ka gusto ato
    public function destroy($itemId)
    {
        // Check if the item exists in the cart
        $item = DB::table('cart_items')->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Delete the item from the database
        DB::table('cart_items')->where('id', $itemId)->delete();

    }


    //kani nga function ang mag update sa quantity sa imong product if ever gusto ka mag add
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is valid
        ]);

        // Fetch the cart item using DB facade
        $cartItem = DB::table('cart_items')->where('id', $id)->first();

        // Check if the cart item existsx   
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // Update the cart item quantity in the database
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'quantity' => $request->quantity,
                'updated_at' => now(),
            ]);

        // Recalculate the updated price (assuming 'product_id' is available)
        $product = DB::table('products')->where('id', $cartItem->product_id)->first();

        $updatedPrice = $product->price * $request->quantity;
        return response()->json([
            'message' => 'Quantity updated successfully',
            'quantity' => $request->quantity,
            'updated_price' => $updatedPrice,
        ]);
    }



    //kini nga function ang ma gamit kung mag checkout naka sa imong cart para kung ma checkout na, 
    //e delete na niya sa cart ang ibutang niya sa transaction na table
    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'paymentMethod' => 'required|string',
            'gcashNumber' => 'nullable|string',
            'total' => 'required|numeric|min:0',
        ]);
        $userId = Auth::id(); // Get authenticated user's ID

        try {
            DB::beginTransaction();
            $cartItems = DB::table('cart_items')
                ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
                ->where('carts.user_id', $userId)
                ->select('cart_items.*')
                ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('No items found in the cart for this user.');
                }                 

            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'total_amount' => $validated['total'],
                'gcash_no' => $validated['gcashNumber'],
                'payment_method' => $validated['paymentMethod'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($cartItems as $cartItem) {
                DB::table('transaction_items')->insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'total_amount' => $validated['total'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $product = DB::table('products')
                    ->where('id', $cartItem->product_id)
                    ->first();

                if (!$product) {
                    throw new \Exception("Product not found for ID: {$cartItem->product_id}");
                }

                if ($product->qty < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $newQty = $product->qty - $cartItem->quantity;

                // Update product quantity
                DB::table('products')
                    ->where('id', $cartItem->product_id)
                    ->update([
                        'qty' => $newQty,
                        'availability' => $newQty > 0,
                    ]);
            }
            // DB::table('transaction_items')->insert([
            //     'transaction_id' => $transactionId,
            //     'product_id' => $cartItems->product_id,
            //     'quantity' => $cart_items->quantity,
            //     'total_amount' => $transactionId->total_amount,
            // ]);
            // Insert the transaction record
            // dd($cartItems);

            // dd($products);
            
            // Clear the user's cart
            DB::table('cart_items')
                ->whereIn('cart_items.cart_id', function ($query) use ($userId) {
                    $query->select('id')
                        ->from('carts')
                        ->where('user_id', $userId);
                })
                ->delete();


            DB::commit();

            return Inertia::location(route('cart'));
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Payment failed, please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    //kini nga function ang mag kuha sa among database view which is transaction_summery para e display niya sa page nga Transaction
    public function transactionDisplay()
    {
        $cartsWithProducts = DB::table('transaction_summary')
            ->where('user_id', Auth::id())
            ->get();
        return Inertia::render('Customer/Transactions', [
            'transactions' => $cartsWithProducts,
        ]);
    }


    //mag delete ka hanggat gusto mo
    public function transactionDestroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
