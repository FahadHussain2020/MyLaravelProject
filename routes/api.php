<?php

use App\Http\Controllers\API\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['controller' => TaskController::class], function () {
    Route::get('tasks', 'index');
    Route::post('tasks/create', 'store');
    Route::get('tasks/{id}', 'show');
    Route::post('tasks/update/{id}', 'update');
    Route::post('tasks/delete/{id}', 'destroy');
    Route::post('tasks/markAsComplete/{id}', 'markAsComplete');
    Route::post('tasks/markAsInComplete/{id}', 'markAsInComplete');
    // Route::apiResource('tasks', TaskController::class);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
