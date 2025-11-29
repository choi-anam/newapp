<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display forgot password form
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request - show channel selection
     */
    public function store(Request $request): RedirectResponse|View
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Show channel selection view
        return view('auth.select-reset-channel', [
            'email' => $request->email,
            'user' => $user,
            'channels' => $this->getAvailableChannels($user),
        ]);
    }

    /**
     * Send OTP via selected channel
     */
    public function sendOtp(Request $request): RedirectResponse
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

        // Validate channel availability
        $availableChannels = $this->getAvailableChannels($user);
        if (!isset($availableChannels[$request->channel])) {
            return back()
                ->withErrors(['channel' => 'Channel tidak tersedia.']);
        }

        // Send reset OTP
        $sent = PasswordResetService::sendReset($user, $request->channel, $request->email);

        if (!$sent) {
            return back()
                ->withInput($request->only('email', 'channel'))
                ->withErrors(['channel' => 'Gagal mengirim OTP. Silakan coba lagi.']);
        }

        return redirect()->route('password.otp.verify', [
            'email' => $request->email,
            'channel' => $request->channel,
        ])->with('status', 'OTP telah dikirim ke ' . $this->getChannelLabel($request->channel));
    }

    /**
     * Get available channels for user
     */
    private function getAvailableChannels(User $user): array
    {
        $channels = [
            'email' => [
                'name' => 'Email',
                'description' => 'Terima kode OTP di email Anda',
                'icon' => '✉️',
                'available' => (bool) $user->email,
            ],
        ];

        // Telegram available if user has telegram_id
        if ($user->telegram_id && config('services.telegram.bot_token')) {
            $channels['telegram'] = [
                'name' => 'Telegram',
                'description' => 'Terima kode OTP di Telegram',
                'icon' => '✈️',
                'available' => true,
            ];
        }

        // WhatsApp available if user has phone number
        if ($user->phone && (config('services.twilio.account_sid') || config('services.whatsapp.api_key'))) {
            $channels['whatsapp'] = [
                'name' => 'WhatsApp',
                'description' => 'Terima kode OTP di WhatsApp',
                'icon' => '💬',
                'available' => true,
            ];
        }

        return $channels;
    }

    /**
     * Get channel label
     */
    private function getChannelLabel(string $channel): string
    {
        return match ($channel) {
            'email' => 'email Anda',
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp',
            default => 'akun Anda',
        };
    }
}
