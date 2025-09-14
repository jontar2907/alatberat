<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TransportationController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\HeavyEquipmentController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;

// ==========================
// Public routes
// ==========================
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/rental/{id}', [LandingController::class, 'requestRental'])->name('rental.request');
Route::post('/rental/store', [RentalRequestController::class, 'store'])->name('rental.store');

// API routes for frontend functionality
Route::get('/api/booked-dates/{equipmentId}', [RentalRequestController::class, 'getBookedDates'])->name('api.booked.dates');

// ==========================
// Authentication routes
// ==========================
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==========================
// Admin routes
// ==========================
Route::middleware(['auth', 'kepala_dinas'])->prefix('kepala-dinas')->name('kepala-dinas.')->group(function () {
    Route::get('/assignments', [\App\Http\Controllers\Admin\AdminController::class, 'assignmentsIndex'])->name('assignments.index');
    Route::get('/assignments/{id}/create', [\App\Http\Controllers\Admin\AdminController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/assignments/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'storeAssignment'])->name('assignments.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Equipment management
    Route::get('/equipments', [AdminController::class, 'equipments'])->name('equipments');
    Route::get('/equipments/export', [AdminController::class, 'exportEquipments'])->name('equipments.export');
    Route::post('/equipments/import', [HeavyEquipmentController::class, 'importEquipmentsExcel'])->name('equipments.import');
    Route::get('/equipments/create', [HeavyEquipmentController::class, 'create'])->name('equipments.create');
    Route::post('/equipments', [HeavyEquipmentController::class, 'store'])->name('equipments.store');
    Route::get('/equipments/{id}/edit', [HeavyEquipmentController::class, 'edit'])->name('equipments.edit');
    Route::put('/equipments/{id}', [HeavyEquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/equipments/{id}', [HeavyEquipmentController::class, 'destroy'])->name('equipments.delete');
    
    // Rental requests management
    Route::get('/rental-requests/{id}/edit', [AdminController::class, 'editRentalRequest'])->name('rental-requests.edit');
    Route::put('/rental-requests/{id}', [AdminController::class, 'updateRentalRequest'])->name('rental-requests.update');
    Route::delete('/rental-requests/{id}', [RentalRequestController::class, 'destroy'])->name('rental-requests.destroy');

    // Routes for jenis sewa create
    Route::get('/jenis-sewa/create', [\App\Http\Controllers\Admin\AdminController::class, 'createJenisSewa'])->name('jenis-sewa.create');
    Route::post('/jenis-sewa', [\App\Http\Controllers\Admin\AdminController::class, 'storeJenisSewa'])->name('jenis-sewa.store');
    Route::post('/verify-request/{id}', [AdminController::class, 'verifyRequest'])->name('verify.request');
    Route::post('/send-invoices-gmail', [AdminController::class, 'sendInvoicesToGmail'])->name('send.invoices.gmail');
    Route::middleware('super_admin')->group(function () {
        Route::get('/create-work-order/{id}', [AdminController::class, 'createWorkOrder'])->name('create.work.order');
        Route::post('/store-work-order/{id}', [AdminController::class, 'storeWorkOrder'])->name('store.work.order');
    });
    
    // Payment management
    Route::post('/verify-payment/{id}', [PaymentController::class, 'verifyPayment'])->name('verify.payment');
    Route::post('/reject-payment/{id}', [PaymentController::class, 'rejectPayment'])->name('reject.payment');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Jenis Sewa management
    Route::get('/jenis-sewa', [AdminController::class, 'jenisSewaIndex'])->name('jenis-sewa.index');

    // Operator management
    Route::get('/operators', [AdminController::class, 'operators'])->name('operators');
    Route::get('/operators/create', [\App\Http\Controllers\OperatorController::class, 'create'])->name('operators.create');
    Route::post('/operators', [\App\Http\Controllers\OperatorController::class, 'store'])->name('operators.store');
    Route::get('/operators/{id}/edit', [\App\Http\Controllers\OperatorController::class, 'edit'])->name('operators.edit');
    Route::put('/operators/{id}', [\App\Http\Controllers\OperatorController::class, 'update'])->name('operators.update');
    Route::delete('/operators/{id}', [\App\Http\Controllers\OperatorController::class, 'destroy'])->name('operators.delete');

    // Transportation management
    Route::resource('transportations', TransportationController::class);

    // Payment management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/payments/export', [AdminController::class, 'exportPayments'])->name('payments.export');
});

// ==========================
// Payment routes (user)
// ==========================
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{id}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment/{id}', [PaymentController::class, 'processPayment'])->name('payment.process');
});

