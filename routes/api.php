<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use  App\Http\Controllers\GoogleSheetController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('sheet', [GoogleSheetController::class, 'index']);
Route::get('student', [StudentController::class, 'student']);
Route::get('/student/{id}', [StudentController::class, 'getStudentDetails']);
Route::post('editstudent', [StudentController::class, 'editstudent']);
Route::post('deletestudent', [StudentController::class, 'deleteStudent']);
