<?php

declare(strict_types=1);

namespace Corephp\Container;

/**
 * Basic container
 * -----------
 * Basic container for get instance of class
 * based on full qualified name
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Container
 */
class BasicContainer
{
    private array $container = [];
    
    /**
     * get
     *
     * @param  mixed $id
     * @param  mixed $options
     * @return void
     */
    public function get(string $id, array $options)
    {
        if ($this->has($id, $options)) {
            if(!$options['shared']){
                $obj = $this->container[$id];
                unset($this->container[$id]);
                return $obj;
            }
            return $this->container[$id];
        }

        throw new \Exception("Class {$id} doesn't exists.");
    }
    
    /**
     * has
     *
     * @param  mixed $id
     * @param  mixed $options
     * @return bool
     */
    public function has(string $id, array $options): bool
    {
        if($options['shared']){
            if (!empty($this->container[$id])) {
                return true;
            }
        }
        if (class_exists($id)) {
            if (empty($options['args'])) {
                $this->container[$id] = new $id();
            }else{
                $args = $options['args'];
                $this->container[$id] = new $id(...$args);
                //$reflect  = new ReflectionClass($id);
                //$this->container[$id] = $reflect->newInstanceArgs($args);
            }
            
            return true;            
        }

        return false;
    }
}
