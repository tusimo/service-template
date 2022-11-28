<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Controller\Base;

use Psr\Http\Message\ResponseInterface;
use Tusimo\Resource\Entity\ResourceRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Tusimo\Resource\Entity\ResourceResponse;
use App\Contract\Service\BaseServiceContract;
use Tusimo\Resource\Contract\ResourceControllerContract;

/**
 * @Controller
 */
abstract class RestController extends AbstractController implements ResourceControllerContract
{
    protected $service;

    public function showRoute()
    {
        return $this->response($this->show($this->request));
    }

    public function getRoute()
    {
        return $this->response($this->get($this->request));
    }

    public function aggregateRoute()
    {
        return $this->response($this->aggregate($this->request));
    }

    /**
     * Rest post route.
     *
     * @return ResponseInterface
     */
    public function storeRoute()
    {
        return $this->response($this->store($this->request));
    }

    /**
     * Rest put route.
     *
     * @return ResponseInterface
     */
    public function updateRoute()
    {
        return $this->response($this->update($this->request));
    }

    /**
     * Rest delete route.
     *
     * @return ResponseInterface
     */
    public function destroyRoute()
    {
        return $this->response($this->destroy($this->request));
    }

    /**
     * Rest show route.
     *
     * @return ResponseInterface
     */
    public function indexRoute()
    {
        return $this->response($this->index($this->request));
    }

    /**
     * Get Resource.
     */
    public function show(ResourceRequest $request): ResourceResponse
    {
        $resourceId = $request->getRouteResourceId();
        $method = 'get';
        if (is_array($resourceId)) {
            $method = 'getByIds';
        }
        $resource = $this->getService()->{$method}(
            $resourceId,
            $request->getSelect(),
            $request->getWith()
        );
        if ($resource) {
            return response()->success($resource);
        }
        return response()->notFound();
    }

    /**
     * Add Resource.
     */
    public function store(ResourceRequest $request): ResourceResponse
    {
        if (! $request->isBatch()) {
            $resource = $request->getResource();
            if (! $resource) {
                return response()->error('resource is empty');
            }
            $resource = $this->getService()
                ->transformToResource($resource);
            $result = $this->getService()->add($resource);

            return response()->success($result);
        }
        $resources = $request->getResources();
        if (! $resources) {
            return response()->error('resources is empty');
        }
        $resources = $this->getService()
            ->transformToResources($resources);
        $result = $this->getService()->batchAdd($resources);
        return response()->created($result);
    }

    /**
     * Update Resource.
     */
    public function update(ResourceRequest $request): ResourceResponse
    {
        if (! $request->isBatch()) {
            $resource = $this->getService()
                ->transformToResource($request->getResource());
            $result = $this->getService()->update(
                $request->getResourceId(),
                $resource
            );
            return response()->success($result);
        }
        $resources = $this->getService()
            ->transformToResources($request->getResources());
        $result = $this->getService()->batchUpdate($resources);
        return response()->success($result);
    }

    /**
     * Destroy Resource.
     */
    public function destroy(ResourceRequest $request): ResourceResponse
    {
        $resourceId = $request->getRouteResourceId();
        $method = 'delete';
        if (is_array($resourceId)) {
            $method = 'deleteByIds';
        }
        $result = $this->getService()->{$method}($resourceId);
        if ($result) {
            return response()->noContent();
        }
        return response()->error('delete resource error', 400);
    }

    /**
     * List Resource with Paginate or Get All Resource by Filter.
     */
    public function index(ResourceRequest $request): ResourceResponse
    {
        $query = $request->getQuery();
        $result = $this->getService()->list($query);
        return response()->paginator($result);
    }

    public function get(ResourceRequest $request): ResourceResponse
    {
        $query = $request->getQuery();
        $result = $this->getService()->getByQuery($query);
        return response()->success($result);
    }

    public function aggregate(ResourceRequest $request): ResourceResponse
    {
        $query = $request->getQuery();
        $result = $this->getService()->aggregate($query);
        return response()->success($result);
    }

    protected function getService(): BaseServiceContract
    {
        return $this->service;
    }

    protected function response(ResourceResponse $resourceResponse): ResponseInterface
    {
        return $resourceResponse->attachToResponse($this->response);
    }
}
