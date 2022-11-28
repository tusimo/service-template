<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component\Logger;

use Monolog\Processor\ProcessorInterface;

/**
 * Injects value of gethostname in all records.
 */
class RequestContextProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $record): array
    {
        $record['request_context'] = request_context()->toArray();
        if (request()->hasRequest()) {
            $record['request'] = [
                'path' => request()->path(),
                'method' => request()->getMethod(),
                'uri' => request()->url(),
                'host' => request()->getUri()->getHost(),
                'port' => request()->getUri()->getPort(),
                'query' => request()->getUri()->getQuery(),
            ];
        }

        return $record;
    }
}
