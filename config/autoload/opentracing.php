<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Zipkin\Samplers\BinarySampler;

use const Jaeger\SAMPLER_TYPE_CONST;

return [
    'default' => env('TRACER_DRIVER', 'zipkin'),
    'enable' => [
        'guzzle' => env('TRACER_ENABLE_GUZZLE', true),
        'redis' => env('TRACER_ENABLE_REDIS', true),
        'db' => env('TRACER_ENABLE_DB', true),
        'method' => env('TRACER_ENABLE_METHOD', true),
    ],
    'tracer' => [
        'zipkin' => [
            'driver' => \Hyperf\Tracer\Adapter\ZipkinTracerFactory::class,
            'app' => [
                'name' => env('APP_NAME', 'service-template'),
                // Hyperf will detect the system info automatically as the value if ipv4, ipv6, port is null
                'ipv4' => null,
                'ipv6' => null,
                'port' => null,
            ],
            'options' => [
                'endpoint_url' => env('ZIPKIN_ENDPOINT_URL', 'http://localhost:9411/api/v2/spans'),
                'timeout' => env('ZIPKIN_TIMEOUT', 1),
            ],
            'sampler' => BinarySampler::createAsAlwaysSample(),
        ],
        'jaeger' => [
            'driver' => \Hyperf\Tracer\Adapter\JaegerTracerFactory::class,
            'name' => env('APP_NAME', 'service-template'),
            'options' => [
                // 采样器，默认为所有请求的都追踪
                'sampler' => [
                    'type' => SAMPLER_TYPE_CONST,
                    'param' => true,
                ],
                'local_agent' => [
                    'reporting_host' => env('JAEGER_REPORTING_HOST', 'localhost'),
                    'reporting_port' => env('JAEGER_REPORTING_PORT', 5775),
                ],
            ],
        ],
    ],
    'tags' => [
        // HTTP 客户端 (Guzzle)
        'http_client' => [
            'http.url' => 'http.url',
            'http.method' => 'http.method',
            'http.status_code' => 'http.status_code',
        ],
        // Redis 客户端
        'redis' => [
            'arguments' => 'arguments',
            'result' => 'result',
        ],
        // 数据库客户端 (hyperf/database)
        'db' => [
            'db.query' => 'db.query',
            'db.statement' => 'db.statement',
            'db.query_time' => 'db.query_time',
        ],
    ],
];
