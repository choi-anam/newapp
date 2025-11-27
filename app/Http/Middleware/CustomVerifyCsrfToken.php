<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class CustomVerifyCsrfToken extends Middleware
{

    protected function tokensMatch($request)
    {
        $token = $request->header(strtoupper(Str::slug((string) env('APP_NAME'))) . '-T'); // ambil dari header kustom

        if (!$token) {
            $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        }

        return is_string($request->session()->token()) &&
            is_string($token) &&
            hash_equals($request->session()->token(), $token);
    }

    protected function addCookieToResponse($request, $response): Response
    {
        $config = config('session');
        $expires = $config['lifetime'] ?? 120; // fallback ke 120 menit

        $response->headers->setCookie(
            cookie(
                strtoupper(Str::slug((string) env('APP_NAME'))) . '-T', // Ganti nama default XSRF-TOKEN
                $request->session()->token(),
                $expires,
                $config['path'],
                $config['domain'],
                $config['secure'] ?? false,
                true, // httponly
                false,
                $config['same_site'] ?? null
            )
        );

        return $response;
    }
}
