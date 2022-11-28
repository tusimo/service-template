<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Model;

use Hyperf\Database\Model\Model;

/**
 * App\Models\BaseModel.
 *
 * @method static Builder|BaseModel newModelQuery()
 * @method static Builder|BaseModel newQuery()
 * @method static Builder|BaseModel query()
 * @mixin Model
 */
abstract class BaseModel extends Model
{
    protected $guarded = [];
}
