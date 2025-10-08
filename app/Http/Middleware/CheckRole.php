<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            \Log::info('CheckRole: User not authenticated, redirecting to login');
            return redirect()->route('index');
        }

        $user = Auth::user();
        \Log::info('CheckRole: User authenticated with role: ' . $user->role . ', Required roles: ' . implode(',', $roles));
        
        // Super admin has access to everything
        if ($user->role === 'super_admin') {
            \Log::info('CheckRole: Super admin access granted');
            return $next($request);
        }
        
        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            \Log::warning('CheckRole: User role ' . $user->role . ' not in required roles: ' . implode(',', $roles));
            abort(403, 'Unauthorized action.');
        }

        \Log::info('CheckRole: Access granted for role: ' . $user->role);
        return $next($request);
    }
} 