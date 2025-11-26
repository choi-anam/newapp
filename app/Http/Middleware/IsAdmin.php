<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Allow users with role 'admin' or 'super-admin'
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
