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

Route::middleware(['auth'])->get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::middleware(['auth', 'yeaf_active'])->group(function () {
    Route::resource('students', App\Http\Controllers\Yeaf\StudentController::class);
    Route::resource('institutions', App\Http\Controllers\Yeaf\InstitutionController::class);
    Route::resource('comments', App\Http\Controllers\Yeaf\CommentController::class);

    Route::resource('grants', App\Http\Controllers\Yeaf\GrantController::class);
    Route::group(
        [
            'prefix' => 'grants',
            'as' => 'grants.'
        ], function () {
        Route::post('/evaluate/{grant}', [App\Http\Controllers\Yeaf\GrantController::class, 'evaluateApp'])->name('evaluate');
        Route::get('/validate-letter/{grant}', [App\Http\Controllers\Yeaf\GrantController::class, 'validateLetter'])->name('validate-letter');
        Route::get('/export-letter/{grant}/{docName?}', [App\Http\Controllers\Yeaf\GrantController::class, 'exportLetter'])->name('export-letter');
    });

    //authenticated admin routes
    Route::group(
        [
            'prefix' => 'maintenance',
            'middleware' => 'yeaf_admin',
            'as' => 'maintenance.'
        ], function () {
            Route::get('/staff', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'staffList'])->name('staff.list');
            Route::get('/staff/{user}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'staffShow'])->name('staff.show');
            Route::post('/staff/{user}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'staffEdit'])->name('staff.edit');

            Route::get('/ineligibles', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'ineligiblesList'])->name('ineligibles.list');
            Route::post('/ineligibles', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'ineligibleStore'])->name('ineligible.store');
            Route::post('/ineligibles/{ineligible}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'ineligibleEdit'])->name('ineligible.edit');

            Route::get('/program-years', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'programYearsList'])->name('program-years.list');
            Route::post('/program-years', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'programYearStore'])->name('program-year.store');
            Route::post('/program-years/{programYear}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'programYearEdit'])->name('program-year.edit');


            Route::get('/batches', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'batchesList'])->name('batches.list');
            Route::post('/batches', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'batchStore'])->name('batches.store');
            Route::post('/batches/{batch}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'batchEdit'])->name('batches.edit');

            Route::get('/ministry', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'ministryShow'])->name('ministry.show');
            Route::post('/ministry/{admin}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'ministryUpdate'])->name('ministry.update');

            Route::get('/reports/{type?}', [App\Http\Controllers\Yeaf\ReportController::class, 'index'])->name('reports.index');

            Route::get('/letters', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'letters'])->name('letters.index');
            Route::get('/download-letters/{type}/{extra}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'downloadLetter'])->name('letters.download');
    });
//    });
});
