<?php

// app/Http/Controllers/Api/HealthController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Test the database connection.
     */
    public function testConnection(): JsonResponse
    {
        try {
            // Attempt to ping/execute a basic query on the DB driver
            DB::connection()->getPdo();

            return response()->json([
                'status'  => 200,
                'message' => 'connection success',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'connection failed',
                // Optional: set to config('app.debug') so full errors don't leak in production
                'error'   => config('app.debug') ? $e->getMessage() : 'Unable to connect to database.',
            ], 500);
        }
    }
}