<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Controller;

use App\Controller\Base\AbstractController;

class IndexController extends AbstractController
{
    public function index()
    {
        $method = $this->request->getMethod();
        $warnings = [];
        if (env('SCAN_CACHEABLE') && is_local()) {
            $warnings['scan_cacheable'] = 'set to true in local,so code can be scanned when code is changed';
        }
        return [
            'method' => $method,
            'query' => $this->request->getUri()->getQuery(),
            'app' => env('APP_NAME'),
            'environment' => environment(),
            'config' => [
                'scan_cacheable' => env('SCAN_CACHEABLE'),
                'apollo' => [
                    'APOLLO_CONFIG_SERVER' => env('APOLLO_CONFIG_SERVER'),
                    'APOLLO_APP_ID' => env('APOLLO_APP_ID'),
                    'APOLLO_NAMESPACES' => env('APOLLO_NAMESPACES'),
                    'APOLLO_CLUSTER' => env('APOLLO_CLUSTER'),
                ],
                'servers' => config('servers'),
                'services' => config('services'),
            ],
            'warnings' => $warnings,
            'message' => 'RD Framework',
            'tips' => [
                'command: <composer watch> can start a local server ',
                'command: <composer cs-fix> can fix code format ',
                'command: <composer cs> can check code format ',
                'command: <composer test> can run phpunit test ',
                'command: <composer analyse> can analyse code',
            ],
        ];
    }
}
