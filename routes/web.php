<?php

use App\Http\Controllers\DashboardController;
use App\Http\Livewire\Admin\Documents\ListDocuments as AdminListDocuments;
use App\Http\Livewire\Users\Documents\ListDocuments as UsersListDocuments;
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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::group(['prefix' => '/', 'as' => 'users.', 'middleware' => 'isUser'], function () {
        Route::get('/', function () {
            return view('users/home');
        })->name('index');
        Route::prefix('documents')->group(function () {
            Route::get('/', UsersListDocuments::class)->name('documents.index');
        });
    });

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'isAdmin'], function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::get('users', ListUsers::class)->name('users');
        Route::prefix('documents')->group(function () {
            Route::get('/', AdminListDocuments::class)->name('documents.index');
            Route::get('jenis', ListJenisDocument::class)->name('documents.jenis');
        });
    });
});
