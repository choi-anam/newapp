<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramBotConfig;
use Illuminate\Http\Request;

class TelegramBotConfigController extends Controller
{
    /**
     * Display telegram bot configuration page
     */
    public function index()
    {
        $configs = TelegramBotConfig::all();
        
        // Get from .env if no database records
        if ($configs->isEmpty()) {
            $envBotToken = config('services.telegram.bot_token');
            if ($envBotToken) {
                $configs = collect([
                    (object)[
                        'id' => null,
                        'bot_token' => $envBotToken,
                        'bot_name' => 'Default Bot',
                        'webhook_url' => null,
                        'is_enabled' => true,
                        'description' => 'Default Telegram Bot from .env',
                    ]
                ]);
            }
        }

        return view('admin.settings.telegram-bot', compact('configs'));
    }

    /**
     * Store telegram bot configuration
     */
    public function store(Request $request)
    {
        $request->validate([
            'bot_token' => 'required|string|min:10',
            'bot_name' => 'required|string|max:255',
            'webhook_url' => 'nullable|url',
            'is_enabled' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        TelegramBotConfig::create([
            'bot_token' => $request->input('bot_token'),
            'bot_name' => $request->input('bot_name'),
            'webhook_url' => $request->input('webhook_url'),
            'is_enabled' => $request->has('is_enabled'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.settings.telegram-bot.index')
            ->with('success', 'Telegram bot configuration added successfully.');
    }

    /**
     * Update telegram bot configuration
     */
    public function update(Request $request, TelegramBotConfig $config)
    {
        $request->validate([
            'bot_token' => 'required|string|min:10',
            'bot_name' => 'required|string|max:255',
            'webhook_url' => 'nullable|url',
            'is_enabled' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $config->update([
            'bot_token' => $request->input('bot_token'),
            'bot_name' => $request->input('bot_name'),
            'webhook_url' => $request->input('webhook_url'),
            'is_enabled' => $request->has('is_enabled'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.settings.telegram-bot.index')
            ->with('success', 'Telegram bot configuration updated successfully.');
    }

    /**
     * Delete telegram bot configuration
     */
    public function destroy(TelegramBotConfig $config)
    {
        $config->delete();

        return redirect()->route('admin.settings.telegram-bot.index')
            ->with('success', 'Telegram bot configuration deleted successfully.');
    }

    /**
     * Toggle configuration status via AJAX
     */
    public function toggle(TelegramBotConfig $config)
    {
        $config->update(['is_enabled' => ! $config->is_enabled]);

        return response()->json([
            'success' => true,
            'is_enabled' => $config->is_enabled,
        ]);
    }
}
