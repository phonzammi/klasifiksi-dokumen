<?php

use App\Http\Controllers\DashboardController;
use App\Http\Livewire\Admin\Documents\ListDocuments;
use App\Http\Livewire\Admin\Documents\ListJenisDocument;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Users\ListUsers;

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

Route::get('/', function () {
    return view('users/home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::prefix('admin/')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('admin.dashboard');
        Route::get('users', ListUsers::class)->name('admin.users');
        Route::prefix('documents')->group(function () {
            Route::get('/', ListDocuments::class)->name('admin.documents.index');
            Route::get('jenis', ListJenisDocument::class)->name('admin.documents.jenis');
        });
    });
});
