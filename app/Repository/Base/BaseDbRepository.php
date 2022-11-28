<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Repository\Base;

use App\Component\Logger\LoggerTrait;
use Tusimo\Resource\Job\Base\QueueTrait;
use Tusimo\Resource\Repository\ModelRepository;
use App\Contract\Repository\BaseRepositoryContract;

abstract class BaseDbRepository extends ModelRepository implements BaseRepositoryContract
{
    use LoggerTrait;
    use QueueTrait;
}
