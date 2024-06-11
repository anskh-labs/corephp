<?php

declare(strict_types=1);

namespace Corephp\Helper;

use Corephp\Container\ConfigContainer;
use Exception;

/**
 * Config
 * -----------
 * Class for working with @see Corephp\Config\Config
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Helper
 */
class Config
{
    private static ?ConfigContainer $container = null;
    
    /**
     * init
     *
     * @param  mixed $configDir
     * @return void
     */
    public static function init(string $configDir): void
    {
        if (static::$container === null) {
            static::$container = make(ConfigContainer::class, ['args' => [$configDir]]);
        }
    }
    
    /**
     * get
     *
     * @param  mixed $offset
     * @param  mixed $defaultValue
     * @return mixed
     */
    public static function get(mixed $offset, mixed $defaultValue = null): mixed
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }
        return static::has($offset) ? static::$container[$offset] : $defaultValue;
    }
    
    /**
     * set
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public static function set(mixed $offset, mixed $value): void
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }
        static::$container[$offset] = $value;
    }
    
    /**
     * has
     *
     * @param  mixed $offset
     * @return bool
     */
    public static function has(mixed $offset): bool
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }
        return static::$container->offsetExists($offset);
    }   
}
