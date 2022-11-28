<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use App\Crontab\HelloWorld;
use App\Crontab\HelloWorld2;

return [
    // 是否开启定时任务
    'enable' => env('ENABLE_CRON', false),
    'crontab' => [
        // (new HelloWorld())->everySeconds(),
        // (new HelloWorld2())->everyMinutes(),
    ],
];
