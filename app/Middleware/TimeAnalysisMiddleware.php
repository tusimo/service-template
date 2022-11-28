<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Middleware;

use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TimeAnalysisMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (is_production()) {
            return $handler->handle($request);
        }

        $startTime = microtime(true);

        $response = $handler->handle($request);
        $endTime = microtime(true);

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);
        $data['meta']['time_analysis'] = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $endTime - $startTime,
        ];
        $response = $response->withBody(new SwooleStream(json_encode($data)));
        Context::set(ResponseInterface::class, $response);
        return $response;
    }
}
