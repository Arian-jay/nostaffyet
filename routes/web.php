<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google Authentication Routes
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['auth', 'setDB'])->group(function () {

    Route::get('/dashboard', function () {
        return match (Auth::user()->role_id) {
            1 => Inertia::render('Admin/AdminDashboard'),
            2 => Inertia::render('Staff/StaffDashboard'),
            3 => redirect()->route('dash'),
            default => abort(403),
        };
    })->name('dashboard');

    Route::get('/user', [AdminController::class, 'users'])->name('user');

    // Admin Controller
    Route::middleware('admin')->group(function () {
        Route::get('Admin/Managemenu', [AdminController::class, 'viewMenu'])->name('managemenu');
        Route::put('Admin/Manage-menu/update/{id}', [AdminController::class, 'updateMenu'])->name('menu.update');
        
        Route::get('Admin/AccountManagement', [AdminController::class, 'viewAccountManagement'])->name('accountmanagement');// Update menu item details
        Route::post('Admin/AccountManagement/Insert', [AdminController::class, 'insertIntoAccountManagement'])->name('account.store');
        Route::get('Admin/AccountManagement/Display', [AdminController::class, 'displayUsers'])->name('users.grouped');
        Route::put('Admin/Account/{id}', [AdminController::class, 'updateUsers'])->name('account.update');
        Route::delete('Admin/Account/{id}', [AdminController::class, 'destroyUsers'])->name('account.destroy');
        
        Route::get('Admin/ProductManagement', [AdminController::class, 'viewProductManagement'])->name('productmanagement');// Update menu item details
        Route::get('Admin/ProductManagement/Display', [AdminController::class, 'displayProducts'])->name('products.index');
        Route::post('Admin/ProductManagement/Insert', [AdminController::class, 'insertProducts'])->name('products.store'); 
        Route::put('Admin/ProductManagement/Update/{id}', [AdminController::class, 'updateProducts'])->name('products.update');
        Route::delete('Admin/ProductManagement/Products/{id}', [AdminController::class, 'deleteProduct'])->name('products.destroy');
    
        Route::get('/Admin/OrderManagement', [AdminController::class, 'viewOrder'])->name('ordermanagement');
        Route::get('/Admin/Orders', [AdminController::class, 'displayOrder'])->name('orders.index');
        Route::put('/Admin/Orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::get('/Admin/Orders/History', [AdminController::class, 'getOrderHistory'])->name('orders.history');
        Route::delete('/admin/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
        Route::post('/Admin/accept', [AdminController::class, 'acceptOrder'])->name('order.accept');
        Route::post('/Admin/ship', [AdminController::class, 'shipOrder'])->name('order.ship');
        Route::post('/Admin/complete', [AdminController::class, 'completeOrder'])->name('order.complete');
        Route::get('/Admin/Order-Details', [AdminController::class, 'orderDetails'])->name('order.details');

    });


    // Staff Controller
    Route::middleware('staff')->group(function (){
        Route::get('Staff/MenuManagement', [StaffController::class,'displayMenu'])->name('staff.menu');
        Route::put('Staff/Menu/{id}/Update-Qty', [StaffController::class, 'updateMenu'])->name('staff.menu.update');
       
        
        Route::get('Staff/OrderManagement', [StaffController::class,'viewOrder'])->name('staff.order');
        Route::post('/staff/orders/{order}/accept', [StaffController::class, 'acceptOrder'])->name('staff.orders.accept');
        Route::put('/staff/orders/{order}', [StaffController::class, 'updateOrder'])->name('staff.orders.update');
        Route::delete('/staff/orders/{order}', [StaffController::class, 'destroyOrder'])->name('staff.orders.destroy');
    });

    Route::middleware('customer')->group(function (){
        Route::get('/menu', [CustomerController::class, 'menuDisplay'])->name('menu');
        Route::get('/dash', [CustomerController::class, 'customerDashboardDisplay'])->name('dash');
        Route::get('/search', [CustomerController::class, 'search'])->name('search');
        Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::get('/cart', [CustomerController::class, 'getCartsWithProducts'])->name('cart');
        Route::delete('/cart/destroy/{id}', [CustomerController::class, 'destroy'])->name('cart.destroy');
        Route::put('/cart/{id}', [CustomerController::class, 'update']);
        Route::post('/transactions/pay', [CustomerController::class, 'submitPayment'])->name('transactions');
        // Route::get('/transactions/{id}', [CustomerController::class, 'transactionDestroy'])->name('transaction.destroy');
        Route::get('/user/transactions', [CustomerController::class, 'transactionDisplay'])->name('transactions.page');
        Route::get('/contact', function () {
            $role = Auth::user()->role_id;
        
            if ($role === 3) {
                return Inertia::render('Contact');
            }
        })->name('contact');
    });
    
});

require __DIR__ . '/auth.php';
