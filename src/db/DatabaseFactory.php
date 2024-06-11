<?php

declare(strict_types=1);

namespace Corephp\Db;

use Corephp\Helper\Config;

/**
 * Database factory
 * -----------
 * Database factory to create instance of 
 * Database class
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Db
 */
class DatabaseFactory
{
    private static array $db = [];
    
    /**
     * create
     *
     * @param  mixed $name
     * @return Database
     */
    static public function create(string $name): Database
    {
        if(!array_key_exists($name, static::$db)){
            $config = config("database.connections.{$name}");
            static::$db[$name] = make(Database::class, ['args' => $config, 'shared' => false]);
        }
        return static::$db[$name];
    } 
}