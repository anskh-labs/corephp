<?php

declare(strict_types=1);

namespace Corephp\Http\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Corephp\Helper\Url;

use function FastRoute\simpleDispatcher;

/**
 * Router
 * -----------
 * Router
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Router
 */
class Router
{
    private array $routes = [];
    private string $basePath = '';
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->basePath = Url::getBasePath();
        $this->routes = config('route');
    }    
    /**
     * getDispatcher
     *
     * @return Dispatcher
     */
    public function getDispatcher(): Dispatcher
    {
        $routes = $this->routes;
        $basePath = $this->basePath;
        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes, $basePath) {
            foreach ($routes as $name => $route) {
                list($method, $path, $handler) = $route;
                $path = $basePath . $path;
                $r->addRoute($method, $path, $handler);
            }
        });
        return $dispatcher;
    }
}
