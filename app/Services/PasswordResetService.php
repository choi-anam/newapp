<?php

namespace App\Services;

use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Notifications\SendPasswordResetOtpNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PasswordResetService
{
    /**
     * Generate OTP token
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send password reset via selected channel
     */
    public static function sendReset(User $user, string $channel, string $email): bool
    {
        try {
            // Generate OTP
            $otp = self::generateOtp();

            // Create OTP record
            $passwordOtp = PasswordResetOtp::create([
                'user_id' => $user->id,
                'email' => $email,
                'otp' => $otp,
                'channel' => $channel,
                'expires_at' => now()->addMinutes(15),
                'is_used' => false,
            ]);

            // Send via selected channel
            if ($channel === 'email') {
                $user->notify(new SendPasswordResetOtpNotification($otp, $channel, $email));
            } elseif ($channel === 'telegram') {
                self::sendTelegramMessage($user, $otp);
            } elseif ($channel === 'whatsapp') {
                self::sendWhatsAppMessage($user, $otp);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Password reset send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify OTP and reset password
     */
    public static function verifyAndReset(User $user, string $email, string $otp, string $newPassword): array
    {
        // Get valid OTP
        $passwordOtp = PasswordResetOtp::where('user_id', $user->id)
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$passwordOtp) {
            return [
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa.',
            ];
        }

        try {
            // Mark OTP as used
            $passwordOtp->markAsUsed();

            // Update password
            $user->update(['password' => $newPassword]);

            // Clean up other OTPs for this user
            PasswordResetOtp::where('user_id', $user->id)
                ->where('email', $email)
                ->delete();

            return [
                'success' => true,
                'message' => 'Password berhasil direset. Silakan login dengan password baru Anda.',
            ];
        } catch (\Exception $e) {
            Log::error('Password reset verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset password.',
            ];
        }
    }

    /**
     * Send message via Telegram
     * Requires: TELEGRAM_BOT_TOKEN and user telegram_id
     */
    private static function sendTelegramMessage(User $user, string $otp): bool
    {
        if (!$user->telegram_id) {
            Log::warning('User does not have telegram_id: ' . $user->id);
            return false;
        }

        try {
            $botToken = config('services.telegram.bot_token');
            $chatId = $user->telegram_id;

            $message = "Kode OTP untuk reset password Anda: **{$otp}**\n\n";
            $message .= "Kode ini berlaku selama 15 menit.\n";
            $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.";

            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ];

            $response = Http::post($url, $payload);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram message send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message via WhatsApp
     * Requires: WHATSAPP_API_KEY, WHATSAPP_PHONE_NUMBER_ID
     * Using WhatsApp Business API (Twilio or similar)
     */
    private static function sendWhatsAppMessage(User $user, string $otp): bool
    {
        if (!$user->phone) {
            Log::warning('User does not have phone: ' . $user->id);
            return false;
        }

        try {
            // Example using Twilio
            $accountSid = config('services.twilio.account_sid');
            $authToken = config('services.twilio.auth_token');
            $fromNumber = config('services.twilio.phone_number');

            if (!$accountSid || !$authToken || !$fromNumber) {
                Log::warning('Twilio credentials not configured');
                return false;
            }

            $message = "Kode OTP untuk reset password Anda: {$otp}\n\n";
            $message .= "Kode ini berlaku selama 15 menit.\n";
            $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.";

            $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->post($url, [
                    'From' => $fromNumber,
                    'To' => $user->phone,
                    'Body' => $message,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp message send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify reset token (legacy link-based method)
     */
    public static function verifyResetToken(string $token): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHour())
            ->first();

        return (bool) $record;
    }
}
