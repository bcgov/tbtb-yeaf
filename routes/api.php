<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/login', [App\Http\Controllers\UserController::class, 'apiLogin'])->name('api-login');

Route::get('/students',
    function (Request $request) {
        return Response::json(\App\Models\Student::get());
    }
)->name('api.students');

Route::get('/students-with-grants',
    function (Request $request) {
        return Response::json(\App\Models\Student::with('grants.grantIneligibles', 'comments', 'grants.appeals', 'grants.py')->get());
    }
)->name('api.students-with-grants');

Route::get('/staff',
    function (Request $request) {
        return Response::json(\App\Models\User::get());
    }
)->name('api.staff');

Route::get('/grants',
    function (Request $request) {
        return Response::json(\App\Models\Grant::get());
    }
)->name('api.grants');

Route::get('/comments',
    function (Request $request) {
        return Response::json(\App\Models\Grant::get());
    }
)->name('api.comments');
