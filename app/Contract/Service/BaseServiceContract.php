<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Contract\Service;

use Tusimo\Restable\Query;
use Tusimo\Resource\Resource;
use Tusimo\Resource\Model\Collection;
use Hyperf\Paginator\LengthAwarePaginator;

interface BaseServiceContract
{
    /**
     * Get Resource.
     *
     * @param mixed $id
     * @return \Tusimo\Resource\Resource
     */
    public function get($id, array $select = [], array $with = []);

    /**
     * Get Resources by Ids.
     * @return \Tusimo\Resource\Model\Collection|\Tusimo\Resource\Resource[]
     */
    public function getByIds(array $ids, array $select = [], array $with = []);

    /**
     * Add Resource and return Resource With Id.
     * @param \Tusimo\Resource\Resource $resource
     * @return \Tusimo\Resource\Resource
     */
    public function add($resource);

    /**
     * Batch add resource.
     * @param collection $resources
     * @return \Tusimo\Resource\Model\Collection|\Tusimo\Resource\Resource[]
     */
    public function batchAdd($resources);

    /**
     * Update Resource.
     *
     * @param int|string $id
     * @param \Tusimo\Resource\Resource $name
     * @param \Tusimo\Resource\Resource $resource
     */
    public function update($id, $resource);

    /**
     * Batch Update Resource.
     * @param \Tusimo\Resource\Model\Collection $resources
     * @return \Tusimo\Resource\Model\Collection|\Tusimo\Resource\Resource[]
     */
    public function batchUpdate($resources);

    /**
     * Delete resource.
     *
     * @param int|string $id
     */
    public function delete($id): bool;

    /**
     * Batch delete Resource.
     */
    public function deleteByIds(array $ids): bool;

    /**
     * Get Resource Paginator.
     *
     * @return LengthAwarePaginator
     */
    public function list(Query $query);

    /**
     * Get Resource By Query.
     * @return \Tusimo\Resource\Model\Collection|\Tusimo\Resource\Resource[]
     */
    public function getByQuery(Query $query);

    /**
     * Get Resource By Query.
     * @return array
     */
    public function aggregate(Query $query);

    /**
     * Transform array to resource.
     * @return \Tusimo\Resource\Resource
     */
    public function transformToResource(array $resource);

    /**
     * Transform array to resource.
     * @return \Tusimo\Resource\Model\Collection|\Tusimo\Resource\Resource[]
     */
    public function transformToResources(array $resource);
}
