<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component;

class Router extends \Hyperf\HttpServer\Router\Router
{
    public static function resource(string $resource, string $controller)
    {
        static::addGroup('/' . $resource, function () use ($controller) {
            // 分页接口
            static::get('', $controller . '@indexRoute');
            // 按条件获取资源
            static::get('/_batch', $controller . '@getRoute');
            // 按条件获取资源
            static::get('/_aggregate', $controller . '@aggregateRoute');
            // 获取单个资源
            static::get('/{resource_id}', $controller . '@showRoute');
            // 获取多个资源
            static::get('/{resource_id}/_batch', $controller . '@showRoute');
            // 创建资源
            static::post('', $controller . '@storeRoute');
            // 批量创建资源
            static::post('/_batch', $controller . '@storeRoute');
            // 批量更新资源
            static::put('/_batch', $controller . '@updateRoute');
            // 更新单个资源
            static::put('/{resource_id}', $controller . '@updateRoute');
            // 删除单个资源
            static::delete('/{resource_id}', $controller . '@destroyRoute');
            // 批量删除资源
            static::delete('/{resource_id}/_batch', $controller . '@destroyRoute');
        });
    }
}
