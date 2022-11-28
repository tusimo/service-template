<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Service;

use App\Component\Logger\LoggerTrait;
use Tusimo\Resource\Model\Collection;
use Tusimo\Resource\Job\Base\QueueTrait;
use App\Contract\Service\BaseServiceContract;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use App\Contract\Repository\BaseRepositoryContract;
use App\Exception\Resource\ResourceNotFoundException;

class BaseService implements BaseServiceContract
{
    use LoggerTrait;
    use QueueTrait;

    protected $resourceClass;

    /**
     * Get repo.
     *
     * @return BaseRepositoryContract
     */
    public function getRepository()
    {
        return $this->newResource()->getRepository();
    }

    /**
     * Get the value of resourceClass.
     */
    public function getResourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * Undocumented function.
     *
     * @param mixed $array
     * @return \Tusimo\Resource\Resource
     */
    public function newResource($array = [])
    {
        $class = $this->getResourceClass();
        return new $class($array);
    }

    /**
     * Get Resource.
     *
     * @param mixed $id
     *
     * @return \Tusimo\Resource\Resource
     */
    public function get($id, array $select = [], array $with = [])
    {
        $resource = $this->newResource()
            ->select($select)->with($with)->find($id);
        if ($resource) {
            return $resource;
        }

        throw new ResourceNotFoundException($id);
    }

    /**
     * Get Resources by Ids.
     *
     * @return array<\Tusimo\Resource\Resource>|\Tusimo\Resource\Model\Collection
     */
    public function getByIds(array $ids, array $select = [], array $with = [])
    {
        return $this->newResource()
            ->select($select)->with($with)->find($ids);
    }

    /**
     * Add Resource and return Resource With Id.
     *
     * @param \Tusimo\Resource\Resource $resource
     *
     * @return \Tusimo\Resource\Resource
     */
    public function add($resource)
    {
        $resource->save();

        return $resource;
    }

    /**
     * Batch add resource.
     *
     * @param \Tusimo\Resource\Model\Collection $resources
     *
     * @return array<\Tusimo\Resource\Resource>|\Tusimo\Resource\Model\Collection
     */
    public function batchAdd($resources)
    {
        $collection = new Collection();
        foreach ($resources as $resource) {
            $collection->add($this->add($resource));
        }
        return $collection;
    }

    /**
     * Update Resource.
     *
     * @param int|string $id
     * @param \Tusimo\Resource\Resource $resource
     *
     * @return mixed
     */
    public function update($id, $resource)
    {
        $oldResource = $this->get($id);
        $oldResource->fill($resource->getDirty());

        $oldResource->save();

        return $oldResource;
    }

    /**
     * Batch Update Resource.
     *
     * @param \Tusimo\Resource\Model\Collection $resources
     *
     * @return array<\Tusimo\Resource\Resource>|\Tusimo\Resource\Model\Collection
     */
    public function batchUpdate($resources)
    {
        $results = [];
        foreach ($resources as $resource) {
            try {
                $results[] = $this->update($resource->getKey(), $resource);
            } catch (ResourceNotFoundException $e) {
                continue;
            } catch (\Exception $e) {
                $this->logger->error(
                    'exception occurred while batch updating resources' . $e->getMessage(),
                    $resource->toArray()
                );
                continue;
            }
        }
        return $results;
    }

    /**
     * Delete resource.
     *
     * @param int|string $id
     */
    public function delete($id): bool
    {
        $resource = $this->get($id);
        return (bool) $resource->delete();
    }

    /**
     * Batch delete Resource.
     */
    public function deleteByIds(array $ids): bool
    {
        foreach ($ids as $id) {
            try {
                $this->delete($id);
            } catch (ResourceNotFoundException $e) {
                continue;
            } catch (\Exception $e) {
                $this->logger->error(
                    'exception occurred while deleting resources' . $e->getMessage(),
                    $ids
                );
                continue;
            }
        }
        return true;
    }

    /**
     * Get Resource Paginator.
     *
     * @return LengthAwarePaginatorInterface
     */
    public function list(\Tusimo\Restable\Query $query)
    {
        $builder = $this->newResource()->query()->setQueryFromBaseQuery($query);
        return $builder->paginate($query->hasQueryPagination() ? $query->getQueryPagination()->getPerPage() : 10);
    }

    /**
     * Get Resource By Query.
     *
     * @return array<\Tusimo\Resource\Resource>|\Tusimo\Resource\Model\Collection
     */
    public function getByQuery(\Tusimo\Restable\Query $query)
    {
        return $this->newResource()->query()->setQueryFromBaseQuery($query)
            ->get();
    }

    /**
     * Get Resource By Query.
     *
     * @return array
     */
    public function aggregate(\Tusimo\Restable\Query $query)
    {
        return $this->newResource()->getRepository()->aggregate($query);
    }

    /**
     * Transform array to resource.
     *
     * @return \Tusimo\Resource\Resource
     */
    public function transformToResource(array $resource)
    {
        return $this->newResource($resource);
    }

    /**
     * Transform to resources collection.
     *
     * @return Collection
     */
    public function transformToResources(array $resources)
    {
        $collection = new Collection();
        foreach ($resources as $resource) {
            $collection->add($this->transformToResource($resource));
        }
        return $collection;
    }
}
