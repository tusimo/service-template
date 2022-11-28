<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
return [
    /*
     * The host to use when listening for debug server connections.
     */
    'host' => env('DUMP_SERVER_HOST', 'tcp://127.0.0.1:9912'),
];
