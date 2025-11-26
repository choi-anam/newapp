<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogSetting;
use Illuminate\Http\Request;

class ActivityLogSettingController extends Controller
{
    /**
     * Display activity log settings page
     */
    public function index()
    {
        // Get all available models from config
        $configuredModels = config('activity-logger.models', []);
        
        // Get existing settings from DB
        $settings = ActivityLogSetting::all()->keyBy('model_class');

        // Build list of models with their current settings
        $models = collect();
        foreach ($configuredModels as $modelClass) {
            $setting = $settings->get($modelClass);
            
            // Create or update the setting if not in DB
            if (! $setting) {
                $setting = ActivityLogSetting::firstOrCreate([
                    'model_class' => $modelClass,
                ], [
                    'enabled' => true,
                ]);
            }

            $models->push([
                'model_class' => $modelClass,
                'short_name' => class_basename($modelClass),
                'enabled' => $setting->enabled,
                'tracked_attributes' => $setting->tracked_attributes,
                'id' => $setting->id,
            ]);
        }

        return view('admin.settings.activity-log', compact('models'));
    }

    /**
     * Update activity log settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'array',
            'settings.*.id' => 'required|exists:activity_log_settings,id',
            'settings.*.enabled' => 'required|boolean',
            'settings.*.tracked_attributes' => 'nullable|string',
        ]);

        foreach ($request->input('settings', []) as $setting) {
            $attrs = null;
            if ($request->input("settings.{$setting['id']}.tracked_attributes")) {
                // Parse comma-separated attributes into array
                $attrs = array_map('trim', explode(',', $request->input("settings.{$setting['id']}.tracked_attributes")));
                $attrs = array_filter($attrs); // Remove empty strings
                $attrs = ! empty($attrs) ? $attrs : null;
            }

            ActivityLogSetting::find($setting['id'])->update([
                'enabled' => $setting['enabled'],
                'tracked_attributes' => $attrs,
            ]);
        }

        return redirect()->route('admin.settings.activity-log.index')
            ->with('success', 'Activity log settings updated successfully.');
    }

    /**
     * Toggle single model tracking via AJAX
     */
    public function toggle(ActivityLogSetting $setting)
    {
        $setting->update(['enabled' => ! $setting->enabled]);

        return response()->json([
            'success' => true,
            'enabled' => $setting->enabled,
        ]);
    }
}
