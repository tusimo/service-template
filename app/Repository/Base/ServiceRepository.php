<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Repository\Base;

use Psr\Log\LoggerInterface;
use Hyperf\Config\Annotation\Value;
use Hyperf\Utils\ApplicationContext;
use Tusimo\Resource\Repository\ApiRepository;
use Tusimo\Resource\Resolver\PoolClientResolver;

class ServiceRepository extends ApiRepository
{
    /**
     * @Value("services")
     */
    protected array $services;

    /**
     * Service name.
     */
    protected string $service;

    protected bool $debug = true;

    public function __construct()
    {
        parent::__construct(
            $this->services[$this->service] ?? '',
            $this->resourceName,
        );
        $this->setLogger(ApplicationContext::getContainer()->get(LoggerInterface::class));
        $this->setClientResolver(new PoolClientResolver());
    }
}
