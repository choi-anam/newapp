<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\EmailCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// API Routes for password reset
Route::post('/api/check-email', [EmailCheckController::class, 'checkEmail'])->name('api.check-email');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Admin Routes
Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Permission management for users
    Route::get('/users/{user}/permissions', [\App\Http\Controllers\Admin\UserController::class, 'showPermissions'])->name('users.permissions');
    Route::post('/users/{user}/permissions/give', [\App\Http\Controllers\Admin\UserController::class, 'givePermission'])->name('users.permissions.give');
    Route::post('/users/{user}/permissions/revoke', [\App\Http\Controllers\Admin\UserController::class, 'revokePermission'])->name('users.permissions.revoke');
    Route::post('/users/{user}/permissions/copy', [\App\Http\Controllers\Admin\UserController::class, 'copyPermissions'])->name('users.permissions.copy');
    
    // Users API & Export
    Route::get('users-data', [\App\Http\Controllers\Admin\UserController::class, 'getData'])->name('users.data');
    Route::get('users-export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    Route::get('users-online-data', [\App\Http\Controllers\Admin\UserController::class, 'onlineData'])->name('users.online-data');
    
    // Activity logs (admin)
    Route::get('activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/management', [\App\Http\Controllers\Admin\ActivityController::class, 'management'])->name('activities.management');
    Route::get('activities-archives', [\App\Http\Controllers\Admin\ActivityController::class, 'archives'])->name('activities.archives');
    Route::get('activities-archives-export', [\App\Http\Controllers\Admin\ActivityController::class, 'exportArchives'])->name('activities.archives-export');
    Route::delete('activities-archives/{archive}', [\App\Http\Controllers\Admin\ActivityController::class, 'destroyArchive'])->name('activities.archives-destroy');
    Route::post('activities-archives/bulk-delete', [\App\Http\Controllers\Admin\ActivityController::class, 'bulkDeleteArchives'])->name('activities.archives-bulk-delete');
    Route::post('activities-archives/bulk-restore', [\App\Http\Controllers\Admin\ActivityController::class, 'bulkRestoreArchives'])->name('activities.archives-bulk-restore');
    Route::get('activities-data', [\App\Http\Controllers\Admin\ActivityController::class, 'getData'])->name('activities.data');
    Route::get('activities-export', [\App\Http\Controllers\Admin\ActivityController::class, 'export'])->name('activities.export');
    Route::post('activities/archive', [\App\Http\Controllers\Admin\ActivityController::class, 'archive'])->name('activities.archive');
    Route::post('activities/cleanup', [\App\Http\Controllers\Admin\ActivityController::class, 'cleanup'])->name('activities.cleanup');
    Route::post('activities/truncate', [\App\Http\Controllers\Admin\ActivityController::class, 'truncate'])->name('activities.truncate');
    Route::post('activities-archives/{archive}/restore', [\App\Http\Controllers\Admin\ActivityController::class, 'restoreArchive'])->name('activities.restore-archive');
    Route::get('activities/{activity}', [\App\Http\Controllers\Admin\ActivityController::class, 'show'])->name('activities.show');
    // Activity log settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('activity-log', [\App\Http\Controllers\Admin\ActivityLogSettingController::class, 'index'])->name('activity-log.index');
        Route::post('activity-log', [\App\Http\Controllers\Admin\ActivityLogSettingController::class, 'update'])->name('activity-log.update');
        Route::post('activity-log/{setting}/toggle', [\App\Http\Controllers\Admin\ActivityLogSettingController::class, 'toggle'])->name('activity-log.toggle');
        
        // Telegram Bot Configuration
        Route::get('telegram-bot', [\App\Http\Controllers\Admin\TelegramBotConfigController::class, 'index'])->name('telegram-bot.index');
        Route::post('telegram-bot', [\App\Http\Controllers\Admin\TelegramBotConfigController::class, 'store'])->name('telegram-bot.store');
        Route::put('telegram-bot/{config}', [\App\Http\Controllers\Admin\TelegramBotConfigController::class, 'update'])->name('telegram-bot.update');
        Route::delete('telegram-bot/{config}', [\App\Http\Controllers\Admin\TelegramBotConfigController::class, 'destroy'])->name('telegram-bot.destroy');
        Route::post('telegram-bot/{config}/toggle', [\App\Http\Controllers\Admin\TelegramBotConfigController::class, 'toggle'])->name('telegram-bot.toggle');
        
        // Email Configuration
        Route::get('email-config', [\App\Http\Controllers\Admin\EmailConfigController::class, 'index'])->name('email-config.index');
        Route::post('email-config', [\App\Http\Controllers\Admin\EmailConfigController::class, 'store'])->name('email-config.store');
        Route::put('email-config/{config}', [\App\Http\Controllers\Admin\EmailConfigController::class, 'update'])->name('email-config.update');
        Route::delete('email-config/{config}', [\App\Http\Controllers\Admin\EmailConfigController::class, 'destroy'])->name('email-config.destroy');
        Route::post('email-config/{config}/toggle', [\App\Http\Controllers\Admin\EmailConfigController::class, 'toggle'])->name('email-config.toggle');
        Route::post('email-config/test', [\App\Http\Controllers\Admin\EmailConfigController::class, 'test'])->name('email-config.test');
    });
});

require __DIR__.'/auth.php';
