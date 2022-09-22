<?php

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

Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('get-logout');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::get('/app-login', [App\Http\Controllers\UserController::class, 'appLogin'])->name('app-login');

Route::middleware(['auth', 'active'])->group(function () {

//    Route::get('/dashboard', [App\Http\Controllers\StudentController::class, 'index'])->name('dashboard');
    Route::resource('students', App\Http\Controllers\StudentController::class);

//    Route::resource('case-funding', App\Http\Controllers\CaseFundingController::class);

    //authenticated admin routes
    Route::group(['middleware' => ['admin']], function () {

        Route::name('maintenance.')->group(function () {
//            Route::get('/maintenance/schools', [App\Http\Controllers\MaintenanceController::class, 'staffList'])->name('schools.list');
            Route::get('/maintenance/staff', [App\Http\Controllers\MaintenanceController::class, 'staffList'])->name('staff.list');
            Route::get('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffShow'])->name('staff.show');
            Route::post('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffEdit'])->name('staff.edit');

            Route::get('/maintenance/ministry', [App\Http\Controllers\MaintenanceController::class, 'ministryShow'])->name('ministry.show');
            Route::post('/maintenance/ministry', [App\Http\Controllers\MaintenanceController::class, 'ministryEdit'])->name('ministry.edit');

//            Route::prefix('maintenance')->group(function () {
//                Route::resource('ministry', App\Http\Controllers\MaintenanceController::class);
//                Route::resource('school', App\Http\Controllers\InstitutionController::class);
//            });
        });
    });
});
//require __DIR__.'/auth.php';
