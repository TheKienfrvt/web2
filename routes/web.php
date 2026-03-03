<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\InventotyController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\SupplierController;

// ==================== ROUTE CÔNG KHAI (KHÔNG CẦN ĐĂNG NHẬP) ====================

Route::get('/',       [HomeController::class, 'index'])->name('home'); // Hiển thi trang chủ
Route::get('/search', [HomeController::class, 'search'])->name('search'); // Hiển thị trang tìm kiếm sản phẩm
Route::get('/category/{category_id}/products',[ProductController::class, 'indexByCategory'])->name('product.indexByCategory');
Route::get('/product/{productId}',  [ProductController::class, 'show'])->name('product.show'); // Chi tiết sản phẩm

Route::get( '/register',  [AuthController::class, 'showRegisterForm'])->name("register"); // Hiển thị form đăng ký khách hàng
Route::post('/register',  [AuthController::class, 'register'])->name("register.submit"); //Xử lý đăng ký khách hàng
Route::get( '/login',     [AuthController::class, 'showLoginForm'])->name('login'); // Hiển thị form đăng nhập khách hàng
Route::post('/login',     [AuthController::class, 'login'])->name('login.submit'); // Xử lý dữ đăng nhập khách hàng

// ==================== ROUTE KHÁCH HÀNG (CẦN ĐĂNG NHẬP) ====================
Route::middleware(['customer'])->group(function() {
  Route::post('/logout',              [AuthController::class, 'logout'])      ->name('logout'); // Xử lý đăng xuất
  Route::get( '/profile',             [AuthController::class, 'showProfile']) ->name('profile.show'); // Trang cá nhân
  Route::put( '/profile/{user_id}',   [AuthController::class, 'update'])      ->name('profile.update'); // Cập nhật thông tin của khách hàng

  // Route quản lý địa chỉ
  Route::get(   '/address',           [AddressController::class, 'index'])  ->name('address'); // Trang địa chỉ
  Route::post(  '/address',           [AddressController::class, 'store'])  ->name('address.store'); // Thêm địa chỉ
  Route::put(   '/address/{address}', [AddressController::class, 'update']) ->name('address.update'); // Cập nhật địa chỉ
  Route::delete('/address/{address}', [AddressController::class, 'delete']) ->name('address.delete'); // Xóa địa chỉ

  // Route quản lý giỏ hàng
  Route::get(   '/cart',          [CartController::class, 'index'])         ->name('cart.index');
  Route::post(  '/cart',          [CartController::class, 'store'])         ->name('cart.store');
  Route::put(   '/cart',          [CartController::class, 'updateQuantity'])->name('cart.update');
  Route::get(   '/checkout',      [CartController::class, 'checkout'])      ->name('checkout.index'); // hiển thị trang thanh toán
  Route::delete('/cart-item/{productId}',   [CartController::class, 'deleteCartItem'])->name('cart.delete');

  
  Route::get(   '/order',         [OrderController::class, 'index'])->name('order.index'); // hiển thị trang đơn hàng
  Route::post(  '/order',         [OrderController::class, 'store'])->name('order.store'); // xử lý đặt hàng
  Route::get(   '/order/cancel/{order}',    [OrderController::class, 'cancel'])   ->name('order.cancel'); // hủy đơn hàng
  Route::get(   '/order/delivered/{order}', [OrderController::class, 'delivered'])->name('order.delivered'); // xác nhận đã nhận hàng
});

// ==================== ROUTE QUẢN TRỊ ====================
Route::prefix('admin')->group(function () {
  // Route đăng nhập (không cần middleware)
  Route::get( '/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login-form');
  Route::post('/login', [AdminAuthController::class, 'login'])        ->name('admin.login');

  // Route cần đăng nhập admin
  Route::middleware(['employee'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout'); // Đăng xuất admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard'); // Trang dashboard admin

    // Route quản lý sản phẩm
    Route::get(   '/product',                  [AdminProductController::class, 'index'])  ->name('admin.product.index');
    Route::get(   '/product/create',           [AdminProductController::class, 'create']) ->name('admin.product.create');
    Route::post(  '/product',                  [AdminProductController::class, 'store'])  ->name('admin.product.store');
    Route::get(   '/product/{productId}',      [AdminProductController::class, 'show'])   ->name('admin.product.show');
    Route::get(   '/product/{productId}/edit', [AdminProductController::class, 'edit'])   ->name('admin.product.edit');
    Route::put(   '/product/{productId}',      [AdminProductController::class, 'update']) ->name('admin.product.update');
    Route::delete('/product/{productId}',      [AdminProductController::class, 'destroy'])->name('admin.product.delete');

    // Route quản lý người dùng
    Route::get(   '/user',                      [UserController::class, 'index'])        ->name('admin.user.index');
    Route::get(   '/user/create',               [UserController::class, 'create'])       ->name('admin.user.create');
    Route::post(  '/user',                      [UserController::class, 'store'])        ->name('admin.user.store');
    Route::get(   '/user/{userId}',             [UserController::class, 'show'])         ->name('admin.user.show');
    Route::get(   '/user/{userId}/edit',        [UserController::class, 'edit'])         ->name('admin.user.edit');
    Route::put(   '/user/{userId}',             [UserController::class, 'update'])       ->name('admin.user.update');
    Route::patch( '/user/{userId}/status',      [UserController::class, 'toggleStatus']) ->name('admin.user.status');
    Route::delete('/user/{userId}',             [UserController::class, 'destroy'])      ->name('admin.user.delete');

    // Route quản lý đơn hàng
    Route::get(   '/order',                         [AdminOrderController::class, 'index'])         ->name('admin.order.index');
    Route::get(   '/order/create',                  [AdminOrderController::class, 'create'])        ->name('admin.order.create');
    Route::post(  '/order',                         [AdminOrderController::class, 'store'])         ->name('admin.order.store');
    Route::get(   '/order/{orderId}',               [AdminOrderController::class, 'show'])          ->name('admin.order.show');
    Route::post(  '/order/{orderId}/confirm',       [AdminOrderController::class, 'confirmOrder'])  ->name('admin.order.confirm');
    Route::post(  '/order/{orderId}/delivery',      [AdminOrderController::class, 'deliveryOrder']) ->name('admin.order.delivery');
    Route::post(  '/order/{orderId}/cancel',        [AdminOrderController::class, 'cancelOrder'])   ->name('admin.order.cancel');
    Route::put(   '/order/{orderId}/updateStatus',  [AdminOrderController::class, 'updateStatus'])  ->name('admin.order.update-status');


    // Route quản lý kho hàng
    Route::get(   '/inventory',                 [InventotyController::class, 'index'])  ->name('admin.inventory.index');
    Route::post(  '/inventory/adjust',          [InventotyController::class, 'adjust']) ->name('admin.inventory.adjust');

    // Route quản lý phiếu nhập
    Route::get(   '/receipt',                   [ReceiptController::class, 'index'])        ->name('admin.receipt.index');
    Route::get(   '/receipt-form',              [ReceiptController::class, 'create'])       ->name('admin.receipt.create');
    Route::post(  '/receipt',                   [ReceiptController::class, 'store'])        ->name('admin.receipt.store');
    Route::get(   '/receipt/{receiptId}',       [ReceiptController::class, 'show'])         ->name('admin.receipt.show');
    Route::patch( '/receipt/{receiptId}/status',[ReceiptController::class, 'updateStatus']) ->name('admin.receipt.update-status');

    // Route quản lý nhà cung cấp
    Route::get(   '/supplier',                  [SupplierController::class, 'index']) ->name('admin.supplier.index');
    Route::post(  '/supplier',                  [SupplierController::class, 'store']) ->name('admin.supplier.store');
    Route::get(   '/supplier/{supplierId}',     [SupplierController::class, 'show'])  ->name('admin.supplier.show');
    Route::put(   '/supplier/{supplierId}',     [SupplierController::class, 'update'])->name('admin.supplier.update');
  });
  
  Route::middleware(['admin'])->group(function () {
    // Route quản lý nhân sự
    Route::get('/employee', [EmployeeController::class, 'index'])->name('admin.employee.index');
    // Route config
    Route::get('/config', [ConfigurationController::class, 'index'])->name('admin.config.index');
    Route::get('/config/category-status/{categoryId}', [ConfigurationController::class, 'changeCategoryStatus'])->name('admin.config.category-status');
  });
});


// Route::get('/category/{category_id}', [CategoryController::class, 'show'])->name('category.show'); // danh sách sản phẩm theo phân loại
// Route::get('/user/show', [UserController::class, 'show'])->name('user.show');
// Route::get('/admin/hack', [AdminController::class, 'createAdmin'])->name('admin.hank');