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
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::resource('institutions', App\Http\Controllers\InstitutionController::class);
    Route::resource('grants', App\Http\Controllers\GrantController::class);
    Route::resource('comments', App\Http\Controllers\CommentController::class);
    Route::post('/grants/evaluate/{grant}', [App\Http\Controllers\GrantController::class, 'evaluateApp'])->name('grants.evaluate');
    Route::get('/grants/validate-letter/{grant}', [App\Http\Controllers\GrantController::class, 'validateLetter'])->name('grants.validate-letter');
    Route::get('/grants/export-letter/{grant}/{docName?}', [App\Http\Controllers\GrantController::class, 'exportLetter'])->name('grants.export-letter');


    //authenticated admin routes
    Route::group(['middleware' => ['admin']], function () {
        Route::name('maintenance.')->group(function () {
            Route::get('/maintenance/staff', [App\Http\Controllers\MaintenanceController::class, 'staffList'])->name('staff.list');
            Route::get('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffShow'])->name('staff.show');
            Route::post('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffEdit'])->name('staff.edit');

            Route::get('/maintenance/ineligibles', [App\Http\Controllers\MaintenanceController::class, 'ineligiblesList'])->name('ineligibles.list');
            Route::post('/maintenance/ineligibles', [App\Http\Controllers\MaintenanceController::class, 'ineligibleStore'])->name('ineligible.store');
            Route::post('/maintenance/ineligibles/{ineligible}', [App\Http\Controllers\MaintenanceController::class, 'ineligibleEdit'])->name('ineligible.edit');

            Route::get('/maintenance/ministry', [App\Http\Controllers\MaintenanceController::class, 'ministryShow'])->name('ministry.show');
            Route::post('/maintenance/ministry', [App\Http\Controllers\MaintenanceController::class, 'ministryEdit'])->name('ministry.edit');

            Route::get('/maintenance/reports/{type?}', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        });
    });
});
