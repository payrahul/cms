<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SemesterController;
use App\Http\Middleware\AgeMiddleware;
use App\Events\UrlHitEvent;
use App\Jobs\CountNumbersJob;
use App\Facades\UserFacade;
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

    // Route::get('user-create', [UserController::class, 'index'])->name('users');
    // Route::post('user-store', [UserController::class, 'store'])->name('user.store');
    Route::post('user-search', [UserController::class, 'search'])->name('user.search');
    Route::get('get-user-list', [UserController::class, 'getUserList']);

    // Only Admin can manage users
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('user-create', [UserController::class, 'index'])->name('users');
        Route::post('user-store', [UserController::class, 'store'])->name('user.store');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('user.delete');
    });

    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    // Route::delete('/users/{id}', [UserController::class, 'delete'])->name('user.delete');


    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::resource('department', DepartmentController::class)->names('department');
    
    Route::resource('programme', ProgrammeController::class)->names('programme');
    Route::resource('task', TaskController::class)->names('task');

    
    Route::resource('semesters',SemesterController::class);
});

// Route::get('/departents/{department}',[DepartmentController::class, 'showBinding'])->middleware('admin');

Route::middleware(['admin','throttle:2,1'])->group(function(){

    Route::get('departents/{department}',[DepartmentController::class,'showBinding']);

});

// Route::middleware(['admin'])->group(function(){
//     Route::get('route',[Controller::class,'function']);
// });


Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/reset', [UserController::class, 'showResetForm'])->name('reset');
Route::post('/send_reset_password_otp', [UserController::class,'sendResetPasswordOtp'])->name('sendresetpasswordotp');

// Prepareation 

Route::get('/test-middleware',[TestController::class, 'testMiddleware'])->middleware(AgeMiddleware::class);
Route::get('/test-event', function () {
    event(new UrlHitEvent());
    return 'Event triggered';
});

Route::get('get-service-singleton',[TestController::class,'service']);

Route::get('/start-count', function(){
    CountNumbersJob::dispatch();
    return 'Counting job started';
});

Route::get('/test-provider', [TestController::class, 'pay']);

Route::get('/users-list', function(){
    return UserFacade::allUsers();
});

Route::get('/employees',[TestController::class,'employeesData'])
// ->middleware('checkAge')
;

use App\Services\GreetingService;
Route::get('/test-facade', function(){
    return Greeting::greet('tony');
});

