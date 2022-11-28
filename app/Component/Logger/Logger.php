<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component\Logger;

use Hyperf\Logger\Logger as HyperfLogger;

class Logger extends HyperfLogger
{
    public function addRecord(int $level, string $message, array $context = []): bool
    {
        return parent::addRecord($level, $message, $context);
    }
}
