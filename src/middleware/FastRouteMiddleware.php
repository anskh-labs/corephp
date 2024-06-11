<?php

declare(strict_types=1);

namespace Corephp\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Corephp\Exception\MethodNotAllowed;
use Corephp\Exception\RouteNotFound;
use Corephp\Http\Router\Router;

/**
 * FastRouteMiddleware
 * -----------
 * FastRouteMiddleware
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Middleware
 */
class FastRouteMiddleware implements MiddlewareInterface
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(private string $actionAttribute = '__action', private string $hidePath = '/public')
    {
    }

    /**
     * @throws MethodNotAllowed
     * @throws RouteNotFound
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->routeRequest($request);

        return $handler->handle($request);
    }
    
    /**
     * routeRequest
     *
     * @param  mixed $request
     * @return ServerRequestInterface
     */
    private function routeRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $router = make(Router::class);
        $fastRoute = $router->getDispatcher();

        $searchPath = rtrim($this->hidePath, '/') . '/';
        $path = $request->getUri()->getPath();
        if (str_contains($path, $searchPath)) {
            $path = str_replace(rtrim($this->hidePath, '/'), '', $path);
        }
        $route = $fastRoute->dispatch($request->getMethod(), $path);

        if ($route[0] === Dispatcher::NOT_FOUND) {
            throw new RouteNotFound($request->getUri()->getPath());
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowed($request->getMethod());
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $request = $request->withAttribute($this->actionAttribute, $route[1]);

        return $request;
    }
}
