<?php

namespace App\Services;

use App\Models\EmailConfig;

class EmailConfigService
{
    /**
     * Ambil konfigurasi email dari DB jika ada,
     * jika tidak, fallback ke .env (config/mail.php).
     */
    public function getActiveEmailConfig($specificId = null)
    {
        // Jika meminta config tertentu
        if ($specificId) {
            $emailConfig = EmailConfig::find($specificId);
            if ($emailConfig) return $emailConfig;
        }

        // Cari yang enabled
        $emailConfig = EmailConfig::where('is_enabled', true)->first();
        if ($emailConfig) return $emailConfig;

        // Fallback ke file .env (config/mail.php)
        $mailConfig      = config('mail');
        $defaultMailer   = $mailConfig['default'] ?? 'smtp';
        $mailerConfig    = $mailConfig['mailers'][$defaultMailer] ?? [];

        return (object)[
            'id'           => null,
            'mailer'       => $defaultMailer,
            'host'         => $mailerConfig['host'] ?? null,
            'port'         => $mailerConfig['port'] ?? null,
            'username'     => $mailerConfig['username'] ?? null,
            'password'     => $mailerConfig['password'] ?? null,
            'encryption'   => $mailerConfig['scheme'] ?? null,
            'from_address' => $mailConfig['from']['address'] ?? null,
            'from_name'    => $mailConfig['from']['name'] ?? null,
            'is_enabled'  => true,
            'description'  => 'Current configuration from .env',
        ];
    }

    /**
     * Apply configuration ke runtime (config())
     */
    public function applyMailConfig($emailConfig)
    {
        config([
            'mail.default' => $emailConfig->mailer,
            'mail.mailers.' . $emailConfig->mailer => [
                'transport'  => $emailConfig->mailer,
                'host'       => $emailConfig->host,
                'port'       => $emailConfig->port,
                'username'   => $emailConfig->username,
                'password'   => $emailConfig->password,
                'encryption' => $emailConfig->encryption,
            ],
            'mail.from' => [
                'address' => $emailConfig->from_address,
                'name'    => $emailConfig->from_name,
            ],
        ]);
    }
}
