<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model activity observer for models enabled in DB settings
        try {
            // Get models enabled for tracking from database
            $enabledModels = \App\Models\ActivityLogSetting::where('enabled', true)
                ->pluck('model_class')
                ->all();

            foreach ($enabledModels as $model) {
                if (class_exists($model)) {
                    $model::observe(\App\Observers\ModelActivityObserver::class);
                }
            }
        } catch (\Exception $e) {
            // Database not yet migrated or table doesn't exist
            // Fall back to config
            $models = config('activity-logger.models', []);
            foreach ($models as $model) {
                if (class_exists($model)) {
                    $model::observe(\App\Observers\ModelActivityObserver::class);
                }
            }
        }
    }
}
