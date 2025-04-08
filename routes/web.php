<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Database\Query\IndexHint;
use Illuminate\Support\Facades\Route;

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
    return view('layouts.template');
});

Route::get('/level', [LevelController::class, 'index']);

Route::get('/user', [UserController::class, 'index']);

Route::get('/barang', [BarangController::class, 'index']);

Route::get('/kategori', [KategoriController::class, 'index']);

Route::get('/stok', [StokController::class, 'index']);

Route::get('/supplier', [SupplierController::class, 'index']);

Route::get('/penjualan', [PenjualanController::class, 'index']);

Route::get('/detail', [PenjualanDetailController::class, 'index']);