<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\GeminiChatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\User\CodController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    return view('auth.reset-password', ['request' => $request, 'token' => $token]);
})->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

Route::get('/', function () {
    $categories = \App\Models\Category::with('children')->whereNull('parent_id')->get();
    return view('user.welcome', compact('categories'));
})->name('home');

Route::get('/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/reorder/{orderId}', [ProfileController::class, 'reorder'])->name('profile.reorder');
    Route::post('/profile/cancel/{orderId}', [ProfileController::class, 'cancelOrder'])->name('profile.cancel');
    
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('user.cart.add');
    Route::put('/cart/update/{cartId}', [CartController::class, 'update'])->name('user.cart.update');
    Route::put('/cart/update-quantity/{cartId}', [CartController::class, 'updateQuantity'])->name('user.cart.updateQuantity');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('user.cart.remove');
    Route::match(['get', 'post'], '/checkout', [CartController::class, 'checkout'])->name('user.checkout');
    Route::get('/thank-you', [CartController::class, 'thankYou'])->name('user.thankyou');

    Route::post('/order/place', [CodController::class, 'placeOrder'])->name('user.order.place');

    Route::post('/vnpay/payment', [\App\Http\Controllers\User\VNPayController::class, 'processPayment'])->name('vnpay.payment');
    Route::get('/vnpay/callback', [\App\Http\Controllers\User\VNPayController::class, 'callback'])->name('vnpay.callback');
    Route::get('/payment/history', [\App\Http\Controllers\User\VNPayController::class, 'paymentHistory'])->name('payment.history');

    Route::post('/momo/payment', [\App\Http\Controllers\User\MoMoController::class, 'processPayment'])->name('momo.payment');
    Route::get('/momo/callback', [\App\Http\Controllers\User\MoMoController::class, 'momoCallback'])->name('momo.callback');
});

Route::middleware(['check.role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/import', [AdminProductController::class, 'import'])->name('admin.products.import');
    Route::post('/products/import', [AdminProductController::class, 'importStore'])->name('admin.products.import.store');


    Route::get('/admin/products/search', [AdminProductController::class, 'search'])->name('admin.products.search');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/categories/hierarchy', [CategoryController::class, 'showHierarchy'])->name('admin.categories.hierarchy');

    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);

    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

Route::get('/user/products', [UserProductController::class, 'list'])->name('user.products');
Route::get('/products', [UserProductController::class, 'list'])->name('products.list'); // Sửa từ ProductController
Route::get('/products/{slug}', [UserProductController::class, 'show'])->name('products.show'); // Sửa từ ProductController
Route::post('/products/{slug}/review', [UserProductController::class, 'storeReview'])->name('products.review'); // Sửa từ ProductController

Route::get('/test-email', function () {
    \Illuminate\Support\Facades\Mail::raw('This is a test email.', function ($message) {
        $message->to('recipient@example.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/chat', [GeminiChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [GeminiChatController::class, 'send'])->name('chat.send');