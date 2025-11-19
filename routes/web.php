<?php

use App\Http\Controllers\patientController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('dashboard/pasien')
    ->name('pasien.')
    ->controller(PatientController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/getAllDataPatients', 'getAllDataPatients')->name('data.patient');
    });

Route::prefix('dashboard/kunjungan')
    ->name('kunjungan.')
    ->controller(VisitController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/getAllDataVisit', 'getAllDataVisit')->name('data.patient');
    });


require __DIR__ . '/settings.php';
