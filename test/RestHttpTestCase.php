<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace HyperfTest;

use Hyperf\Testing\Client;
use Tusimo\Restable\Query;
use Tusimo\Resource\Resource;
use Tusimo\Resource\Model\Collection;
use Hyperf\Paginator\LengthAwarePaginator;

/**
 * Class HttpTestCase.
 * @method post($uri, $data = [], $headers = [])
 * @method json($uri, $data = [], $headers = [])
 * @method file($uri, $data = [], $headers = [])
 * @method request($method, $path, $options = [])
 */
abstract class RestHttpTestCase extends HttpTestCase
{
    protected string $resourceClass;

    protected string $version = 'v2';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Client::class);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->context, $name)) {
            $this->context->{$name}(...$arguments);
            return $this;
        }
        return $this->client->{$name}(...$arguments);
    }

    public function get($id, array $select, array $with)
    {
        $query = query()->select($select)->with($with);
        $uri = "/api/{$this->version}/{$this->getResourceName()}/{$id}?" . $query->toUriQueryString();

        $headers = $this->getHeaders();
        $data = $this->client->get($uri, [], $headers);

        return $this->handleResponse($data, []);
    }

    public function getByIds(array $ids, array $select, array $with)
    {
        $idsString = implode(',', $ids);
        $uri = "/api/{$this->version}/{$this->getResourceName()}/{$idsString}";
        $query = [
            'select' => implode(',', $select),
            'with' => implode(',', $with),
        ];
        $headers = $this->getHeaders();
        $data = $this->client->get($uri, $query, $headers);
        return $this->handleResponse($data, []);
    }

    public function add(array $resource)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}";
        $headers = $this->getHeaders();
        $data = $this->client->post($uri, $resource, $headers);
        return $this->handleResponse($data, []);
    }

    public function batchAdd(array $resources)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/_batch";
        $headers = $this->getHeaders();
        $data = $this->client->post($uri, $resources, $headers);
        return $this->handleResponse($data, []);
    }

    public function update($id, array $data)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/{$id}";
        $headers = $this->getHeaders();
        $data = $this->client->put($uri, $data, $headers);
        return $this->handleResponse($data, []);
    }

    public function batchUpdate(array $data)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/_batch";
        $headers = $this->getHeaders();
        $data = $this->client->put($uri, $data, $headers);
        return $this->handleResponse($data, []);
    }

    public function delete($id)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/{$id}";
        $headers = $this->getHeaders();
        $data = $this->client->delete($uri, [], $headers);
        return $this->handleResponse($data, true);
    }

    public function deleteByIds(array $ids)
    {
        $idsString = implode(',', $ids);
        $uri = "/api/{$this->version}/{$this->getResourceName()}/{$idsString}";
        $headers = $this->getHeaders();
        $data = $this->client->delete($uri, [], $headers);
        return $this->handleResponse($data, true);
    }

    public function list(Query $query)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}?" . $query->toUriQueryString();
        $headers = $this->getHeaders();
        $data = $this->client->get($uri, [], $headers);
        return $this->handleResponse($data, true);
    }

    public function getByQuery(Query $query)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/_batch?" . $query->toUriQueryString();
        $headers = $this->getHeaders();
        $data = $this->client->get($uri, [], $headers);
        return $this->handleResponse($data, []);
    }

    public function aggregate(Query $query)
    {
        $uri = "/api/{$this->version}/{$this->getResourceName()}/_aggregate?" . $query->toUriQueryString();
        $headers = $this->getHeaders();
        $data = $this->client->get($uri, [], $headers);
        return $this->handleResponse($data, []);
    }

    public function handleResponse($data, $default = null)
    {
        if (empty($data)) {
            return $default;
        }
        $code = $data['code'] ?? 400;
        if ($code < 200 || $code >= 300) {
            return $default;
        }
        if (isset($data['data'])) {
            return $data['data'];
        }
        return $default;
    }

    /**
     * Get a new resource instance.
     */
    protected function getResource(): Resource
    {
        return new $this->resourceClass();
    }

    /**
     * Get current test case resource name.
     */
    protected function getResourceName(): string
    {
        return $this->getResource()->getResourceName();
    }

    /**
     * Covert current data as a resource.
     * @param mixed $exists
     */
    protected function asResource(array $data, $exists = true): Resource
    {
        return $this->getResource()->newInstance($data, $exists);
    }

    /**
     * Covert current data as a resource collection.
     */
    protected function asResourceCollection(array $data): Collection
    {
        return $this->getResource()->newCollection($data);
    }

    /**
     * Covert current data as a resource paginator.
     */
    protected function asResourcePaginator(array $data): LengthAwarePaginator
    {
        $items = $this->asResourceCollection($data['data']);
        $total = $data['meta']['total'];
        $perPage = $data['meta']['per_page'];
        $currentPage = $data['meta']['current_page'];
        return new LengthAwarePaginator($items, $total, $perPage, $currentPage);
    }

    protected function assertIsResourceModel(array $data, Resource $resource): void
    {
        $this->assertResourceKey($data, $resource);
        $this->assertResourceAttributes($data, $resource);
    }

    protected function assertResourceKey(array $data, Resource $resource): void
    {
        $this->assertIsBool($this->asResource($data)->is($resource));
    }

    protected function assertResourceAttributes(array $data, Resource $resource): void
    {
        $this->assertEquals($data, $resource->toArray());
    }
}
