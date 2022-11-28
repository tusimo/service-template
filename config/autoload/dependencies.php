<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Psr\Log\LoggerInterface;
use App\Repository\Token\TokenDbRepository;
use App\Repository\Visit\VisitDbRepository;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\Detail\DetailDbRepository;
use App\Contract\Repository\TokenRepositoryContract;
use App\Contract\Repository\VisitRepositoryContract;
use App\Contract\Repository\DetailRepositoryContract;

return [
    // ServerRequestInterface::class => \App\Entity\ResourceRequest::class,
    LoggerInterface::class => \App\Component\Logger\LoggerFactory::class,

    // Repository Dependency
    TokenRepositoryContract::class => TokenDbRepository::class,
    DetailRepositoryContract::class => DetailDbRepository::class,
    VisitRepositoryContract::class => VisitDbRepository::class,
];
