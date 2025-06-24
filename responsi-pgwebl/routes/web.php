<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicZoneController;
use App\Http\Controllers\GOSController;
use App\Http\Controllers\HealthFacilityController;
use App\Http\Controllers\ParkingZoneController;
use App\Http\Controllers\SSCController;
use App\Http\Controllers\TayogamaRoutesController;
use App\Http\Controllers\UtilityFacilityController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Access Routes
|--------------------------------------------------------------------------
*/

// Halaman home terbuka untuk semua
Route::get('/', function () {
    return view('home');
})->name('home');

// Halaman peta dan tabel bisa diakses guest
Route::get('/map', [AcademicZoneController::class, 'index'])->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');

/*
|--------------------------------------------------------------------------
| Dashboard Laravel Breeze (Login User)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk User yang Sudah Login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Resource routes (CRUD)
    Route::resource('academic-zone', AcademicZoneController::class);
    Route::resource('gos', GOSController::class);
    Route::resource('ssc', SSCController::class);
    Route::resource('health', HealthFacilityController::class);
    Route::resource('utility', UtilityFacilityController::class);
    Route::resource('tayo', TayogamaRoutesController::class);

    // Custom POST routes (untuk create langsung tanpa form resource)
    Route::post('/academicZone', [AcademicZoneController::class, 'store'])->name('academicZone.store');
    Route::post('/academic-zone/store', [AcademicZoneController::class, 'store'])->name('academicZone.store');
    Route::put('/academic-zone/{id}', [AcademicZoneController::class, 'update']);

    Route::post('/gos', [GOSController::class, 'store'])->name('gos.store');
    Route::post('/ssc', [SSCController::class, 'store'])->name('ssc.store');
    Route::post('/health', [HealthFacilityController::class, 'store'])->name('health.store');
    Route::post('/tayo', [TayogamaRoutesController::class, 'store'])->name('tayo.store');
    Route::post('/utility', [UtilityFacilityController::class, 'store'])->name('utility.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login/Register dari Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
