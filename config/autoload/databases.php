<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
return [
    'default' => [
        'driver' => env('DB_DRIVER', 'mysql'),
        // 'host' => env('DB_HOST', 'localhost'),
        'read' => [
            'host' => [
                env('DB_READ_HOST', env('DB_HOST', 'localhost')),
            ],
        ],
        'write' => [
            'host' => [
                env('DB_WRITE_HOST', env('DB_HOST', 'localhost')),
            ],
        ],
        'sticky' => env('DB_STICKY', true),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_DATABASE', 'hyperf'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => env('DB_PREFIX', ''),
        'pool' => [
            'min_connections' => (int) env('DB_POOL_MIN_CONNECTIONS', 1),
            'max_connections' => (int) env('DB_POOL_MAX_CONNECTIONS', 10),
            'connect_timeout' => (float) env('DB_POOL_CONNECT_TIMEOUT', 10.0),
            'wait_timeout' => (float) env('DB_POOL_WAIT_TIMEOUT', 3.0),
            'heartbeat' => (float) env('DB_POOL_HEARTBEAT', -1),
            'max_idle_time' => (float) env('DB_POOL_MAX_IDLE_TIME', 60),
        ],
        'cache' => [
            'handler' => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key' => '{mc:%s:m:%s}:%s:%s',
            'prefix' => 'default',
            'ttl' => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script' => true,
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
                'uses' => '',
                'table_mapping' => [],
            ],
        ],
    ],
];
