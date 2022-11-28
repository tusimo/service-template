<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Controller\Base;

use App\Component\Logger\LoggerTrait;
use Tusimo\Resource\Job\Base\QueueTrait;

abstract class AbstractController extends BaseController
{
    use LoggerTrait;
    use QueueTrait;
}
