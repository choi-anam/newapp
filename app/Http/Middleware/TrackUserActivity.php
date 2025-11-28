<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $user = auth()->user();
            $now = now();
            $shouldUpdate = !$user->last_seen_at || $user->last_seen_at->diffInSeconds($now) >= 60;
            if ($shouldUpdate) {
                $user->forceFill(['last_seen_at' => $now])->save();
            }
        }

        return $response;
    }
}
