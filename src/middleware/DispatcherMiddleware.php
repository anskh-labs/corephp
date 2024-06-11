<?php

declare(strict_types=1);

namespace Corephp\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * DispatcherMiddleware
 * -----------
 * DispatcherMiddleware
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Middleware
 */
class DispatcherMiddleware implements MiddlewareInterface
{   
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(private string $actionAttribute = '__action')
    {
    }
    
    /**
     * process
     *
     * @param  mixed $request
     * @param  mixed $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute($this->actionAttribute);
        $response = $handler->handle($request);

        if (is_array($action) && is_string($action[0]) && is_string($action[1])) {
            $className = $action[0];
            $object = make($className);
            $response = $object->{$action[1]}($request, $response);
        } else {
            if (is_callable($action) === false) {
                $action = make($action);
            }
            $response = $action($request, $response);
        }

        return $response;
    }
}