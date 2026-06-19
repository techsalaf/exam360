<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->route('login');
        }

        $user = Auth::user();

        // ✅ USE PERMISSIONS / ROLES PROPERLY
        if (
            !$user->hasAnyRole(['Super Admin', 'Admin', 'Instructor']) &&
            !$user->can('view_exams')
        ) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized'], 403)
                : abort(403);
        }

        // Banned user
        if ($user->is_banned ?? false) {
            Auth::logout();

            return $request->expectsJson()
                ? response()->json(['message' => 'Account suspended'], 403)
                : redirect()->route('login');
        }

        return $next($request);
    }
}
