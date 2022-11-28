<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Command;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @Command
 */
class BaseCommand extends HyperfCommand
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    protected $name = 'change_me';

    protected $coroutine = true;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    public function enableDispatcher(InputInterface $input)
    {
        // metric will block the command quit, so disable it in command mode
        $this->config->set('metric.default', 'noop');
        // default set enable-event-dispatcher true, so we can get the config from config-center
        $this->eventDispatcher = ApplicationContext::getContainer()->get(EventDispatcherInterface::class);
    }

    public function handle()
    {
        dump(1);
    }
}
