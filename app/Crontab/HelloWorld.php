<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Crontab;

use Tusimo\Resource\Crontab\Cron;

class HelloWorld extends Cron
{
    protected $name = 'HelloWorld';

    public function execute()
    {
        dump('Hello World!');
    }
}
