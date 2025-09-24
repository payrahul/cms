<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProgrammeController;

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

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard','dashboard')->name('dashboard');

    Route::get('user-create', [UserController::class, 'index'])->name('users');
    Route::post('user-store', [UserController::class, 'store'])->name('user.store');
    Route::post('user-search', [UserController::class, 'search'])->name('user.search');
    Route::get('get-user-list', [UserController::class, 'getUserList']);

    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('user.delete');


    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::resource('department', DepartmentController::class)->names('department');
    
    Route::resource('programme', ProgrammeController::class)->names('programme');
});




Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/reset', [UserController::class, 'showResetForm'])->name('reset');
Route::post('/send_reset_password_otp', [UserController::class,'sendResetPasswordOtp'])->name('sendresetpasswordotp');

