<?php

// use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\V1\DataController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hello', [TestController::class, 'index']);
// routes/api.php



Route::post('create',[DataController::class, 'store']);
// Route::prefix('v1')->group(function () {
//     Route::post('/data', [DataController::class, 'store']);
// });
Route::get('users', [DataController::class, 'index']);

Route::put('/users/{id}', [DataController::class, 'updatePut']);

Route::patch('/users/{id}', [DataController::class, 'updatePatch']);




Route::prefix('v1')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register'])->middleware('email.domain');

    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/posts',[AuthController::class, 'index']);
    
    Route::get('/users', [AuthController::class, 'list']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('/subscription-plans/{id}', [UserController::class, 'plan']);
});
    

// Route::middleware('auth:sanctum')->get('/v1/profile', function (Request $request) {
    //     return response()->json([
//         'user' => $request->user(),
//     ]);
// });



//Protected Routes (Uses Passport's 'auth:api' guard)
Route::middleware(['auth:api','role:admin','prepend'])->group(function () {
    Route::get('test-db', [HealthController::class, 'testConnection']);
    Route::post('create',[DataController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
    Route::get('/v1/profile', function (Request $request) {
            return response()->json([
            'user' => $request->user(),
        ]);
    });
});