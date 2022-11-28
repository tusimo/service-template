<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Exception\Resource;

use Tusimo\Resource\Resource;

class ResourceException extends \Exception
{
    /**
     * Resource.
     *
     * @var \Tusimo\Resource\Resource
     */
    protected $resource;

    public function __construct($resource, $message = '', $code = 0, $previous = null)
    {
        $this->resource = $resource;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get resource.
     *
     * @return \Tusimo\Resource\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set resource.
     *
     * @param \Tusimo\Resource\Resource $resource resource
     *
     * @return self
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;

        return $this;
    }
}
