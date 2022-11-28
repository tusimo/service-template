<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Controller\Base;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Tusimo\Resource\Entity\ResourceRequest;
use Hyperf\HttpServer\Contract\ResponseInterface;

abstract class BaseController
{
    /**
     * Container.
     * @Inject
     */
    protected ContainerInterface $container;

    /**
     * Http Request.
     * @Inject
     */
    protected ResourceRequest $request;

    /**
     * Response.
     * @Inject
     */
    protected ResponseInterface $response;
}
