<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component\Logger;

use Psr\Log\LoggerInterface;
use Hyperf\Di\Annotation\Inject;

trait LoggerTrait
{
    /**
     * @Inject
     */
    protected LoggerInterface $logger;

    public function logException(\Exception $exception, $level, $context = []): void
    {
        $this->logger->log($level, $exception->getMessage(), $context);
    }
}
