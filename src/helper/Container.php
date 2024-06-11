<?php

declare(strict_types=1);

namespace Corephp\Helper;

use Corephp\Container\BasicContainer;

/**
 * Container
 * -----------
 * Container class for DI
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Helper
 */
class Container
{
    private static ?BasicContainer $container = null;
        
    /**
     * get
     *
     * @param  mixed $id
     * @param  mixed $options
     * @return void
     */
    public static function get(string $id, array $options)
    {
        if (static::$container === null) {
            static::$container = new BasicContainer();
        }
        return static::$container->get($id, $options);
    }
}
