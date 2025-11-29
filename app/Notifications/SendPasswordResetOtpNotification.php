<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPasswordResetOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $otp;
    public string $channel;
    public string $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp, string $channel, string $email)
    {
        $this->otp = $otp;
        $this->channel = $channel;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return match ($this->channel) {
            'email' => ['mail'],
            'telegram' => ['telegram'],
            'whatsapp' => ['whatsapp'],
            default => ['mail'],
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password OTP')
            ->line('Anda meminta untuk mereset password akun Anda.')
            ->line('Gunakan kode OTP di bawah ini untuk melanjutkan:')
            ->line('')
            ->line('**' . $this->otp . '**')
            ->line('')
            ->line('Kode ini berlaku selama 15 menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->action('Reset Password', route('password.otp.verify', ['email' => $this->email]))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'channel' => $this->channel,
            'expires_at' => now()->addMinutes(15),
        ];
    }
}
