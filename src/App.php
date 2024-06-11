<?php

declare(strict_types=1);

namespace Corephp;

use Corephp\Exception\MethodNotAllowed;
use Corephp\Exception\RouteNotFound;
use Corephp\Helper\Config;
use Corephp\Helper\Service;
use Corephp\Http\Handler\RequestHandler;
use Exception;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * App
 * -----------
 * App
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp
 */
class App implements RequestHandlerRunnerInterface
{
    private ServerRequestInterface $request;
    private RequestHandlerInterface $requestHandler;
    private ResponseInterface $response;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $configDir = 'app' . DS . 'config')
    {
        Config::init($configDir);
        $this->response = Service::response(make(Response::class));
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        try {
            if (config('app.maintenance', false) === true) {
                $view = config('view.maintenance');
                $this->response = $view($this->response);
            } else {
                $this->request = Service::request(ServerRequestFactory::fromGlobals());
                $middleware = config('middleware');
                $this->requestHandler = make(RequestHandler::class, ['args' => [$middleware, $this->response]]);
                $this->response = $this->requestHandler->handle($this->request);
            }
        } catch (Exception $e) {
            if ($e instanceof RouteNotFound) {
                $error = config('error.404');
                $this->response = $error($e, $this->response);
            } elseif ($e instanceof MethodNotAllowed) {
                $error = config('error.403');
                $this->response = $error($e, $this->response);
            } else {
                $error = config('error.500');
                $this->response = $error($e, $this->response);
            }
        }

        if (headers_sent() === false) {
            $emitter = make(SapiEmitter::class);
            $emitter->emit($this->response);
        }
    }
}
