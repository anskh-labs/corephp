<?php

declare(strict_types=1);

namespace Corephp\Helper;

use Corephp\Component\Mail\SMTPSender;
use Corephp\Http\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Service
 * -----------
 * Class for helping access service component
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Helper
 */
class Service
{
    protected static ?ServerRequestInterface $request = null;
    protected static ?ResponseInterface $response = null;
    protected static ?SMTPSender $mail = null;

    /**
     * request
     *
     * @param  mixed $request
     * @return ServerRequestInterface
     */
    public static function request(?ServerRequestInterface $request = null): ServerRequestInterface
    {
        if ($request) {
            static::$request = $request;
        }
        return static::$request;
    }
    /**
     * response
     *
     * @param  mixed $response
     * @return ResponseInterface
     */
    public static function response(?ResponseInterface $response = null): ResponseInterface
    {
        if ($response) {
            static::$response = $response;
        }
        return static::$response;
    }

    /**
     * session
     *
     * @param  mixed $sessionAttribute
     * @return SessionInterface
     */
    public static function session(string $sessionAttribute = '__session'): SessionInterface
    {
        return static::$request->getAttribute($sessionAttribute);
    }
    /**
     * mail
     *
     * @return SMTPSender
     */
    public static function mail(): SMTPSender
    {
        if (!static::$mail) {
            static::$mail = new SMTPSender(config('mail.config'));
        }

        return static::$mail;
    }
    /**
     * sanitize
     *
     * @param  mixed $request
     * @param  mixed $except
     * @return array
     */
    public static function sanitize(ServerRequestInterface $request, string|array $except = ''): array
    {
        $data = [];
        if (is_string($except) && $except) {
            $except = [$except];
        }
        if ($request->getMethod() === 'POST') {
            foreach ($request->getParsedBody() as $key => $value) {
                if ($except && in_array($key, $except)) {
                    $data[$key] = $value;
                } else {
                    if (is_array($value)) {
                        $items = [];
                        foreach ($value as $item) {
                            $items[] = htmlspecialchars($item);
                        }
                        $data[$key] = $items;
                    } else {
                        $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } elseif ($request->getMethod() === 'GET') {
            foreach ($request->getQueryParams() as $key => $value) {
                if ($except && in_array($key, $except)) {
                    $data[$key] = $value;
                } else {
                    if (is_array($value)) {
                        $items = [];
                        foreach ($value as $item) {
                            $items[] = htmlspecialchars($item);
                        }
                        $data[$key] = $items;
                    } else {
                        $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        return $data;
    }
}
