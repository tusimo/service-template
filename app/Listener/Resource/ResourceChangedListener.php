<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Listener\Resource;

use Tusimo\Resource\Model\Events\Event;
use Tusimo\Resource\Model\Events\Created;
use Tusimo\Resource\Model\Events\Deleted;
use Tusimo\Resource\Model\Events\Updated;
use Hyperf\Event\Contract\ListenerInterface;

class ResourceChangedListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            Created::class,
            Updated::class,
            Deleted::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event)
    {
        if ($event instanceof Event) {
            $model = $event->getModel();
            // we will automatically broadcast the event to the bus message queue
        }
    }
}
