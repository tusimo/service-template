<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Exception\Resource;

use Tusimo\Resource\Resource;

class ResourceNotFoundException extends \Exception
{
    /**
     * Resource.
     *
     * @var mixed
     */
    protected $resourceId;

    public function __construct($resourceId, $previous = null)
    {
        $this->resourceId = $resourceId;
        parent::__construct('resource not found :' . $resourceId, 404, $previous);
    }

    /**
     * Get resource.
     *
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set resource.
     *
     * @param mixed $resourceId resource
     *
     * @return self
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }
}
