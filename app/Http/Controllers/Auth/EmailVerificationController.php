<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification prompt.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        // Jika user sudah verified, redirect ke dashboard
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Jika tidak ada user session, redirect ke login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        return view('auth.verify-email');
    }

    /**
     * Verify the email address of the user.
     */
    public function verify(Request $request): RedirectResponse
    {
        $id = $request->route('id');
        $hash = $request->route('hash');

        $user = User::findOrFail($id);

        // Verify hash
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('verification.notice')
                ->with('error', 'Link verifikasi tidak valid.');
        }

        // Already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('status', 'Email Anda sudah diverifikasi sebelumnya.');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login')
            ->with('status', '✅ Email Anda berhasil diverifikasi! Silakan login dengan akun Anda.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Email verifikasi telah dikirim ulang. Silakan periksa inbox Anda.');
    }
}
