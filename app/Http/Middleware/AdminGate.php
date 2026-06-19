<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            return $next($request);
        }

        $allowedPermissions = [
            'view_exams',
            'create_exams',
            'edit_exams',
            'delete_exams',
            'view_users',
            'create_users',
            'change_roles',
            'access_settings',
            'view_questions',
            'import_questions',
            'monitor_live_exams',
            'view_reports',
            'manage_tickets'
        ];

        if ($user->hasAnyPermission($allowedPermissions)) {
            return $next($request);
        }


        if ($user->hasAnyRole(['Instructor', 'Admin', 'Manager'])) {
            return $next($request);
        }

        return redirect()->route('user.dashboard');
    }
}