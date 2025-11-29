<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class VerifyPasswordResetOtpController extends Controller
{
    /**
     * Display OTP verification form
     */
    public function create(Request $request): View
    {
        $email = $request->query('email');
        $channel = $request->query('channel', 'email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'channel' => $channel,
        ]);
    }

    /**
     * Verify OTP and reset password
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'channel' => ['required', 'in:email,telegram,whatsapp'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email', 'otp'))
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Verify OTP and reset password
        $result = PasswordResetService::verifyAndReset(
            $user,
            $request->email,
            $request->otp,
            $request->password
        );

        if (!$result['success']) {
            return back()
                ->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => $result['message']]);
        }

        return redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'channel' => ['required', 'in:email,telegram,whatsapp'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Send new OTP
        $sent = PasswordResetService::sendReset($user, $request->channel, $request->email);

        if (!$sent) {
            return back()
                ->withInput($request->only('email', 'channel'))
                ->withErrors(['channel' => 'Gagal mengirim ulang OTP. Silakan coba lagi.']);
        }

        return back()->with('status', 'OTP baru telah dikirim. Silakan periksa pesan Anda.');
    }
}
