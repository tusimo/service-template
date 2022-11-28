<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Hyperf\ConfigCenter\Mode;
use App\Component\ApolloDriver;
use Hyperf\ConfigApollo\PullMode;

return [
    'enable' => (bool) env('CONFIG_CENTER_ENABLE', false),
    'driver' => env('CONFIG_CENTER_DRIVER', 'apollo'),
    'mode' => env('CONFIG_CENTER_MODE', Mode::PROCESS),
    'drivers' => [
        'apollo' => [
            'driver' => ApolloDriver::class,
            'pull_mode' => PullMode::INTERVAL,
            'server' => env('APOLLO_CONFIG_SERVER', ''),
            'appid' => env('APOLLO_APP_ID', ''),
            'cluster' => env('APOLLO_CLUSTER', 'default'),
            'namespaces' => explode(',', env('APOLLO_NAMESPACES', '')),
            'interval' => 5,
            'strict_mode' => true,
            'client_ip' => current(swoole_get_local_ip()),
            'pullTimeout' => 10,
            'interval_timeout' => 1,
        ],
    ],
];
