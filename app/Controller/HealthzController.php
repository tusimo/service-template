<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Controller;

use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Base\AbstractController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * @Controller
 * @SuppressWarnings(PHPMD)
 */
class HealthzController extends AbstractController
{
    /**
     * @Inject
     */
    private \Hyperf\Guzzle\ClientFactory $clientFactory;

    /**
     * @GetMapping(path="/healthz")
     */
    public function show()
    {
        return [
            'config_service' => 'health',
            'redis_service' => 'health',
            'mysql_service' => 'health',
            'time' => [
                'time_zone' => [
                    'default' => date_default_timezone_get(),
                    'carbon' => Carbon::now()->getTimezone(),
                ],
                'UTC' => Carbon::now('UTC')->toW3cString(),
                'Asia/Shanghai' => Carbon::now('Asia/Shanghai')->toW3cString(),
            ],
        ];
    }
}
