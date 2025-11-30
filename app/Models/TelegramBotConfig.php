<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramBotConfig extends Model
{
    protected $table = 'telegram_bot_configs';

    protected $fillable = [
        'bot_token',
        'bot_name',
        'webhook_url',
        'is_enabled',
        'description',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
