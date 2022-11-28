<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use App\Component\Logger\RequestContextProcessor;

/*
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'handler' => [
            'class' => \Monolog\Handler\StreamHandler::class,
            'constructor' => [
                'stream' => 'php://stdout',
                'level' => \Monolog\Logger::INFO,
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\JsonFormatter::class,
            'constructor' => [],
        ],
        'processors' => [
            [
                'class' => HostnameProcessor::class,
                'constructor' => [],
            ],
            [
                'class' => MemoryUsageProcessor::class,
                'constructor' => [],
            ],
            [
                'class' => MemoryPeakUsageProcessor::class,
                'constructor' => [],
            ],
            [
                'class' => IntrospectionProcessor::class,
                'constructor' => [],
            ],
            [
                'class' => RequestContextProcessor::class,
                'constructor' => [],
            ],
        ],
    ],
];
