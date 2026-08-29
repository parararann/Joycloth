<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\DesignController as AdminDesignController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\DesignController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ==========================================
// PUBLIC ROUTES (Tidak perlu login)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/desain', [DesignController::class, 'index'])->name('designs.index');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');

// ==========================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// USER ROUTES (Perlu login, role: user)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // --- Profile ---
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // --- Keranjang ---
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::put('/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

    // --- Checkout & Pesanan ---
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{orderNumber}/bayar', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/pesanan/{orderNumber}/bayar', [OrderController::class, 'submitPayment'])->name('orders.payment.submit');

    // --- Live Chat ---
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/kirim', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');

    // --- Reviews ---
    Route::post('/reviews', [\App\Http\Controllers\Frontend\ReviewController::class, 'store'])->name('reviews.store');
});

// --- Admin Auth ---
Route::get('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

// ==========================================
// ADMIN ROUTES (Perlu login + role admin)
// ==========================================
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // --- Produk ---
    Route::post('produk/reorder', [AdminProductController::class, 'reorder'])->name('produk.reorder');
    Route::resource('produk', AdminProductController::class);

    // --- Kategori ---
    Route::post('kategori/reorder', [CategoryController::class, 'reorder'])->name('kategori.reorder');
    Route::resource('kategori', CategoryController::class);

    // --- Pesanan ---
    Route::get('pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('pesanan/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('pesanan/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::put('pesanan/{id}/konfirmasi', [AdminOrderController::class, 'confirm'])->name('orders.confirm');

    // --- Pembayaran ---
    Route::get('pembayaran', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('pembayaran/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::put('pembayaran/{id}/verifikasi', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::put('pembayaran/{id}/tolak', [PaymentController::class, 'reject'])->name('payments.reject');

    // --- User ---
    Route::get('pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('pengguna/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('pengguna/{id}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::delete('pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // --- Live Chat Admin ---
    Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('chat/{userId}', [AdminChatController::class, 'conversation'])->name('chat.conversation');
    Route::post('chat/{userId}/balas', [AdminChatController::class, 'reply'])->name('chat.reply');
    Route::get('chat/{userId}/poll', [AdminChatController::class, 'poll'])->name('chat.poll');
    Route::get('chat-unread-count', [AdminChatController::class, 'unreadCount'])->name('chat.unread_count');

    // --- Desain Referensi ---
    Route::post('desain/reorder', [AdminDesignController::class, 'reorder'])->name('desain.reorder');
    Route::resource('desain', AdminDesignController::class);
});
