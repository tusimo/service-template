<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
return [
    'default' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'default',
        ],
        'channel' => env('APP_NAME'),
        'timeout' => 2,
        'retry_seconds' => 5,
        'handle_timeout' => 10,
        'processes' => env('ENABLE_QUEUE') ? (is_production() ? swoole_cpu_num() : 1) : 0,
        'concurrent' => [
            'limit' => 10,
        ],
    ],
];
