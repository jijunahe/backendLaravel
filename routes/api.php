<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\BitacoraController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->get('user', [AuthController::class, 'getAuthenticatedUser']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('refresh', [AuthController::class, 'refresh']);


Route::middleware('auth:api')->group(function () {
    Route::get('empleados', [EmpleadoController::class, 'index']);
    Route::post('empleados', [EmpleadoController::class, 'store']);
    Route::get('empleados/{id}', [EmpleadoController::class, 'show']);
    Route::put('empleados/{id}', [EmpleadoController::class, 'update']);
    Route::delete('empleados/{id}', [EmpleadoController::class, 'destroy']);
});

Route::middleware('auth:api')->get('/bitacora', [BitacoraController::class, 'index']);
