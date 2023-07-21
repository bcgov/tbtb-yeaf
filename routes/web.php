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
Route::get('/twp/maintenance/reports/{type}', [App\Http\Controllers\Twp\ReportController::class, 'fetchReport'])->name('twp.reports.type');
Route::group(
    [
        'middleware' => ['auth', 'twp_active'],
        'prefix' => 'twp',
        'as' => 'twp.',
    ], function () {
        Route::resource('students', App\Http\Controllers\Twp\StudentController::class);
        Route::get('/application-list', [App\Http\Controllers\Twp\StudentController::class, 'apps'])->name('application-list');
        Route::resource('applications', App\Http\Controllers\Twp\ApplicationController::class);
        Route::resource('programs', App\Http\Controllers\Twp\ProgramController::class);
        Route::resource('payments', App\Http\Controllers\Twp\PaymentController::class);
        Route::resource('grants', App\Http\Controllers\Twp\GrantController::class);
        Route::get('/students-letters/{type}/{extra}', [App\Http\Controllers\Twp\ApplicationController::class, 'downloadLetter'])->name('applications.letters.download');
        Route::put('/application-status/{application}', [App\Http\Controllers\Twp\ApplicationController::class, 'applicationStatus'])->name('application-status.update');
        Route::post('/students-letters/{extra}', [App\Http\Controllers\Twp\ApplicationController::class, 'downloadSchoolLetter'])->name('applications.letters.school-download');
        Route::post('/students-transfer-letters/{extra}', [App\Http\Controllers\Twp\ApplicationController::class, 'downloadStudentTransferLetter'])->name('applications.letters.student-transfer-download');

        Route::group(
            [
                'prefix' => 'maintenance',
                'as' => 'maintenance.',
            ], function () {
                Route::get('/staff', [App\Http\Controllers\Twp\MaintenanceController::class, 'staffList'])->name('staff.list');
                Route::get('/staff/{user}', [App\Http\Controllers\Twp\MaintenanceController::class, 'staffShow'])->name('staff.show');
                Route::post('/staff/{user}', [App\Http\Controllers\Twp\MaintenanceController::class, 'staffEdit'])->name('staff.edit');
                Route::get('/institutions', [App\Http\Controllers\Twp\MaintenanceController::class, 'institutionList'])->name('institutions.list');
                Route::post('/institutions', [App\Http\Controllers\Twp\MaintenanceController::class, 'institutionStore'])->name('institutions.store');
                Route::put('/institutions/{institution}', [App\Http\Controllers\Twp\MaintenanceController::class, 'institutionUpdate'])->name('institutions.update');
                Route::get('/reasons', [App\Http\Controllers\Twp\MaintenanceController::class, 'reasonList'])->name('reasons.list');
                Route::post('/reasons', [App\Http\Controllers\Twp\MaintenanceController::class, 'reasonStore'])->name('reasons.store');
                Route::put('/reasons/{reason}', [App\Http\Controllers\Twp\MaintenanceController::class, 'reasonUpdate'])->name('reasons.update');
                Route::get('/payments', [App\Http\Controllers\Twp\MaintenanceController::class, 'paymentList'])->name('payments.list');
                Route::post('/payments', [App\Http\Controllers\Twp\MaintenanceController::class, 'paymentStore'])->name('payments.store');
                Route::put('/payments/{payment}', [App\Http\Controllers\Twp\MaintenanceController::class, 'paymentUpdate'])->name('payments.update');
                Route::get('/reports', [App\Http\Controllers\Twp\ReportController::class, 'reportsShow'])->name('reports.show');
                Route::post('/reports/switch', [App\Http\Controllers\Twp\ReportController::class, 'switchOn'])->name('reports.switch-on');
            });
    });

//## YEAF ROUTES

Route::get('/maintenance/reports/{type}', [App\Http\Controllers\Yeaf\ReportController::class, 'index'])->name('reports.type');
Route::middleware(['auth', 'yeaf_active'])->group(function () {
    Route::resource('students', App\Http\Controllers\Yeaf\StudentController::class);
    Route::resource('institutions', App\Http\Controllers\Yeaf\InstitutionController::class);
    Route::resource('comments', App\Http\Controllers\Yeaf\CommentController::class);
    Route::resource('grants', App\Http\Controllers\Yeaf\GrantController::class);
    Route::group(
        [
            'prefix' => 'grants',
            'as' => 'grants.',
        ], function () {
            Route::get('/validate-letter/{grant}', [App\Http\Controllers\Yeaf\GrantController::class, 'validateLetter'])->name('validate-letter');
            Route::get('/export-letter/{grant}/{docName?}', [App\Http\Controllers\Yeaf\GrantController::class, 'exportLetter'])->name('export-letter');
            Route::post('/evaluate/{grant}', [App\Http\Controllers\Yeaf\GrantController::class, 'evaluateApp'])->name('evaluate');
        });

    //authenticated admin routes
    Route::group(
        [
            'prefix' => 'maintenance',
            'middleware' => 'yeaf_admin',
            'as' => 'maintenance.',
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
            Route::get('/reports', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'reportsShow'])->name('reports.show');
            Route::post('/reports/switch', [App\Http\Controllers\Yeaf\ReportController::class, 'switchOn'])->name('reports.switch-on');
            Route::get('/letters', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'letters'])->name('letters.index');
            Route::get('/download-letters/{type}/{extra}', [App\Http\Controllers\Yeaf\MaintenanceController::class, 'downloadLetter'])->name('letters.download');
        });
});
