<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ShiftReportController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StaffDocumentController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/shift-reports', [ShiftReportController::class, 'store']);
    Route::get('/shift-reports', [ShiftReportController::class, 'index']);
    Route::post('/shift-reports/{shiftReport}/accept', [ShiftReportController::class, 'accept']);
    Route::post('/shift-reports/{shiftReport}/reject', [ShiftReportController::class, 'reject']);
    Route::get('/shift-reports/{shiftReport}/pdf', [ShiftReportController::class, 'downloadPdf']);
    Route::put('/shift-reports/{shiftReport}', [ShiftReportController::class, 'update']);
    Route::delete('/shift-reports/{shiftReport}', [ShiftReportController::class, 'destroy']);
    Route::get('/shift-reports/{shiftReport}', [ShiftReportController::class, 'show']);

    // Your own profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);
    Route::post('/profile/documents', [StaffDocumentController::class, 'store']);
    Route::delete('/staff-documents/{staffDocument}', [StaffDocumentController::class, 'destroy']);

    // Admin/Manager managing staff (Manager view-only, Admin full control)
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::get('/staff/{staff}', [StaffController::class, 'show']);
    Route::put('/staff/{staff}', [StaffController::class, 'update']);
    Route::put('/staff/{staff}/status', [StaffController::class, 'updateStatus']);
    Route::put('/staff/{staff}/password', [StaffController::class, 'resetPassword']);
});