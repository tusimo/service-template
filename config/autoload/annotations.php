<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Hyperf\Utils\Coroutine;

return [
    'scan' => [
        'paths' => [
            BASE_PATH . '/app',
        ],
        'ignore_annotations' => [
            'mixin',
        ],
        // 'class_map' => [
        //     Coroutine::class => BASE_PATH . '/class_map/Hyperf/Utils/Coroutine.php',
        // ],
    ],
];
