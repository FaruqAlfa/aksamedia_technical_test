<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevisionsController;
use App\Http\Controllers\EmployeeController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/divisions', [DevisionsController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{uuid}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{uuid}', [EmployeeController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

?>