<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeavyEquipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin routes for heavy equipment management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.equipments.index');
    })->name('dashboard');

    Route::resource('equipments', HeavyEquipmentController::class)->except(['show']);
    Route::post('equipments/import', [HeavyEquipmentController::class, 'importEquipmentsExcel'])->name('equipments.import');
    Route::get('equipments-export', [HeavyEquipmentController::class, 'export'])->name('equipments.export');
});

// Debug route to check equipment images
Route::get('/debug/equipments', function () {
    $equipments = App\Models\HeavyEquipment::whereNotNull('image')->take(5)->get();
    return view('debug.equipments', compact('equipments'));
});
