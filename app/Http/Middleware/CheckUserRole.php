<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// use Illuminate\Http\Response;
class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Get authenticated user
        $user = $request->user();

        // 2. Debug check: If no user was resolved from token
        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthenticated. Token missing or invalid.',
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        // 3. Case-insensitive role comparison (handles 'Admin' vs 'admin')
        if (strtolower(trim($user->role)) !== strtolower(trim($role))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Forbidden. Required role: ' . $role . ', your role: ' . $user->role,
            ], Response::HTTP_FORBIDDEN); // 403
        }

        return $next($request);
    }
}