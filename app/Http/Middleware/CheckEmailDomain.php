<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;

        // Check if email contains @gmail.com
        if (!str_ends_with($email, '@gmail.com')) {
            return response()->json([
                'status' => 403,
                'message' => 'Only Gmail accounts are allowed.'
            ], 403);
        }

        return $next($request);
    }
}