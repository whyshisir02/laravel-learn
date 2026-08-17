<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;

Route::get('/hello', [TestController::class, 'index']);
