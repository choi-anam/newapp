<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $user = auth()->user();
            $shouldUpdate = $user->last_seen_at === null || $user->last_seen_at->diffInSeconds(now()) >= 60;
            if ($shouldUpdate) {
                // $user->forceFill(['last_seen_at' => now()])->save();

                // don't use forceFill to avoid triggering model events
                DB::table('users')->where('id', $user->id)->update(['last_seen_at' => now()]);

                // Broadcast updated online users snapshot via websocket
                try {
                    $online = \App\Models\User::where('last_seen_at', '>=', now()->subMinutes(5))
                        ->select(['id', 'name', 'email'])
                        ->get()
                        ->map(fn($u) => [
                            'id' => $u->id,
                            'name' => $u->name,
                            'email' => $u->email,
                        ])->toArray();
                    event(new \App\Events\OnlineUsersUpdated($online));
                } catch (\Throwable $e) {
                    // Silently ignore broadcast errors to avoid impacting request
                }
            }
        }

        return $response;
    }
}
