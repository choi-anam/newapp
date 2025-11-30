<?php

use App\Services\EmailConfigService;

if (!function_exists('getActiveEmailConfig')) {
    function getActiveEmailConfig($id = null)
    {
        return app(EmailConfigService::class)->getActiveEmailConfig($id);
    }
}
