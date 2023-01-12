<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Role super admin
Route::group(['middleware' => ['role:super-admin', 'auth', 'verified']], function () {
    Route::prefix('/transactions')->group(function () {
        Route::delete('/', [TransactionController::class, 'delete']);
    });
});

// Role super admin or admin
Route::group(['middleware' => ['role:super-admin|admin', 'auth', 'verified']], function () {
    Route::prefix('/transactions')->group(function () {
        Route::get('/update/{id}', [TransactionController::class, 'showUpdate']);
        Route::patch('/', [TransactionController::class, 'update']);
    });
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/read', [UserController::class, 'read']);
        Route::post('/', [UserController::class, 'create']);
        Route::patch('/', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'delete']);
    });
});

// Role super admin or admin or warehouse
Route::group(['middleware' => ['role:super-admin|admin|warehouse', 'auth', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::prefix('/units')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::get('/read', [UnitController::class, 'read']);
        Route::post('/', [UnitController::class, 'create']);
        Route::patch('/', [UnitController::class, 'update']);
        Route::delete('/', [UnitController::class, 'delete']);
    });
    Route::prefix('/types')->group(function () {
        Route::get('/', [TypeController::class, 'index']);
        Route::get('/read', [TypeController::class, 'read']);
        Route::post('/', [TypeController::class, 'create']);
        Route::patch('/', [TypeController::class, 'update']);
        Route::delete('/', [TypeController::class, 'delete']);
    });
    Route::prefix('/suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/read', [SupplierController::class, 'read']);
        Route::post('/', [SupplierController::class, 'create']);
        Route::patch('/', [SupplierController::class, 'update']);
        Route::delete('/', [SupplierController::class, 'delete']);
    });
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/read', [ProductController::class, 'read']);
        Route::post('/', [ProductController::class, 'create']);
        Route::patch('/', [ProductController::class, 'update']);
        Route::delete('/', [ProductController::class, 'delete']);
    });
    Route::prefix('/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/read', [TransactionController::class, 'read']);
        Route::get('/add', [TransactionController::class, 'showCreate']);
        Route::get('/{id}', [TransactionController::class, 'detail']);
        Route::get('/download/{id}', [TransactionController::class, 'invoice']);
        Route::post('/', [TransactionController::class, 'create']);
    });
    Route::prefix('/profile')->group(function () {
        Route::get('/', [UserController::class, 'showProfile']);
        Route::patch('/update-profile', [UserController::class, 'updateProfile']);
        Route::patch('/change-password', [UserController::class, 'changePassword']);
    });
});



// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
