<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\UnsubscribeController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {

    // Auth routes (public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/autocomplete', [ProductController::class, 'autocomplete']);
    Route::get('/products/brands', [ProductController::class, 'brands']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Reviews (public)
    Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
    Route::get('/products/{productId}/reviews/stats', [ReviewController::class, 'stats']);

    // Coupons (public - validation)
    Route::post('/coupons/validate', [CouponController::class, 'validate']);

    // Shipments (public - tracking)
    Route::get('/shipments/track/{trackingNumber}', [ShipmentController::class, 'trackByNumber']);

    // Contact
    Route::post('/contact', [ContactController::class, 'send']);

    // Unsubscribe (public)
    Route::get('/unsubscribe/{token}', [UnsubscribeController::class, 'unsubscribe']);
    Route::get('/resubscribe/{token}', [UnsubscribeController::class, 'resubscribe']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{productId}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    // Orders - Guest checkout
    Route::post('/orders', [OrderController::class, 'store']);
});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth routes (protected)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'changePassword']);

    // Addresses
    Route::get('/user/addresses', [AddressController::class, 'index']);
    Route::post('/user/addresses', [AddressController::class, 'store']);
    Route::get('/user/addresses/{id}', [AddressController::class, 'show']);
    Route::put('/user/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/user/addresses/{id}', [AddressController::class, 'destroy']);
    Route::put('/user/addresses/{id}/set-default', [AddressController::class, 'setDefault']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::get('/wishlist/product-ids', [WishlistController::class, 'getProductIds']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);

    // Reviews (authenticated)
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/notifications/delete-read', [NotificationController::class, 'deleteRead']);

    // Manager routes (accessible by manager and admin)
    Route::middleware('manager')->group(function () {

        // Dashboard & Statistics
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/sales-stats', [AdminController::class, 'salesStats']);
        Route::get('/admin/products/low-stock', [AdminController::class, 'lowStockProducts']);
        Route::get('/admin/products/out-of-stock', [AdminController::class, 'outOfStockProducts']);

        // File Upload
        Route::post('/upload/image', [FileUploadController::class, 'uploadImage']);
        Route::post('/upload/images', [FileUploadController::class, 'uploadMultiple']);
        Route::delete('/upload/image', [FileUploadController::class, 'deleteImage']);
        Route::delete('/upload/image-by-url', [FileUploadController::class, 'deleteImageByUrl']);

        // Products Management
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Categories Management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Orders Management
        Route::get('/admin/orders', [OrderController::class, 'adminIndex']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Reviews Management
        Route::get('/admin/reviews', [ReviewController::class, 'adminIndex']);
        Route::put('/admin/reviews/{id}/toggle-approval', [ReviewController::class, 'toggleApproval']);

        // Coupons Management
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons', [CouponController::class, 'store']);
        Route::get('/coupons/{id}', [CouponController::class, 'show']);
        Route::put('/coupons/{id}', [CouponController::class, 'update']);
        Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);
        Route::put('/coupons/{id}/toggle-status', [CouponController::class, 'toggleStatus']);
        Route::get('/admin/coupons/stats', [CouponController::class, 'stats']);

        // Shipments Management
        Route::get('/shipments', [ShipmentController::class, 'index']);
        Route::post('/shipments', [ShipmentController::class, 'store']);
        Route::get('/shipments/{id}', [ShipmentController::class, 'show']);
        Route::put('/shipments/{id}', [ShipmentController::class, 'update']);
        Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy']);
        Route::get('/admin/shipments/stats', [ShipmentController::class, 'stats']);

        // Export/Reports
        Route::get('/admin/export/orders/excel', [OrderController::class, 'exportExcel']);
        Route::get('/admin/export/orders/pdf', [OrderController::class, 'exportPdf']);
        Route::get('/admin/export/products/excel', [ProductController::class, 'exportExcel']);
        Route::get('/admin/export/sales-report', [ExportController::class, 'salesReport']);
    });

    // Admin only routes (only accessible by admin)
    Route::middleware('admin')->group(function () {
        // User Management (admin only)
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::put('/admin/users/{id}/role', [AdminController::class, 'updateUserRole']);
        Route::put('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus']);
    });
});
