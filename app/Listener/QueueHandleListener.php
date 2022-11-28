<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Listener;

use Psr\Log\LoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\AsyncQueue\Event\Event;
use Hyperf\AsyncQueue\AnnotationJob;
use Hyperf\Event\Annotation\Listener;
use Hyperf\AsyncQueue\Event\AfterHandle;
use Hyperf\AsyncQueue\Event\RetryHandle;
use Hyperf\AsyncQueue\Event\BeforeHandle;
use Hyperf\AsyncQueue\Event\FailedHandle;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;

/**
 * @Listener
 */
class QueueHandleListener implements ListenerInterface
{
    protected LoggerInterface $logger;

    /**
     * Formatter.
     * @Inject
     */
    protected FormatterInterface $formatter;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('queue');
    }

    public function listen(): array
    {
        return [
            AfterHandle::class,
            BeforeHandle::class,
            FailedHandle::class,
            RetryHandle::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof Event && $event->message->job()) {
            $job = $event->message->job();
            $jobClass = get_class($job);
            if ($job instanceof AnnotationJob) {
                $jobClass = sprintf('Job[%s@%s]', $job->class, $job->method);
            }
            $date = date('Y-m-d H:i:s');

            switch (true) {
                case $event instanceof BeforeHandle:
                    $this->logger->info(sprintf('[%s] Processing %s.', $date, $jobClass));
                    break;
                case $event instanceof AfterHandle:
                    $this->logger->info(sprintf('[%s] Processed %s.', $date, $jobClass));
                    break;
                case $event instanceof FailedHandle:
                    $this->logger->error(sprintf('[%s] Failed %s.', $date, $jobClass));
                    $this->logger->error($this->formatter->format($event->getThrowable()));
                    break;
                case $event instanceof RetryHandle:
                    $this->logger->warning(sprintf('[%s] Retried %s.', $date, $jobClass));
                    break;
            }
        }
    }
}
