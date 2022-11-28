<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component\Logger;

use Psr\Log\LoggerInterface;
use Hyperf\Logger\Exception\InvalidConfigException;
use Hyperf\Logger\LoggerFactory as HyperfLoggerFactory;

class LoggerFactory extends HyperfLoggerFactory
{
    public function __invoke()
    {
        return $this->get('default');
    }

    public function make($name = 'hyperf', $group = 'default'): LoggerInterface
    {
        $config = $this->config->get('logger');
        if (! isset($config[$group])) {
            throw new InvalidConfigException(sprintf('Logger config[%s] is not defined.', $name));
        }

        $config = $config[$group];
        $handlers = $this->handlers($config);
        $processors = $this->processors($config);

        return make(\App\Component\Logger\Logger::class, [
            'name' => $name,
            'handlers' => $handlers,
            'processors' => $processors,
        ]);
    }
}
