<?php

declare(strict_types=1);

namespace Corephp\Container;

use ArrayAccess;

/**
 * Configuration container
 * -----------
 * Configuration container for accessing 
 * app config folder with support dot notation
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Container
 */
class ConfigContainer implements ArrayAccess
{
    private array $container = [];
    private string $configDir;
    private string $environment = CORE_ENVIRONMENT;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $configDir) 
    {
        $this->configDir = $configDir;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool
    {
        if (isset($this->container[$offset])) {
            return true;
        }

        $name = strtok($offset, '.');
        if (isset($this->container[$name])) {
            $p = $this->container[$name];
            while (false !== ($name = strtok('.'))) {
                if (!isset($p[$name])) {
                    return false;
                }

                $p = $p[$name];
            }
            $this->container[$offset] = $p;

            return true;
        } else {
            $file = "{$this->configDir}/{$name}.php";
            if (is_file($file) && is_readable($file)) {
                $this->container[$name] = include $file;
                if ($this->environment) {
                    $file = "{$this->configDir}/{$this->environment}/{$name}.php";
                    if (is_file($file) && is_readable($file)) {
                        $this->container[$name] = array_replace_recursive($this->container[$name], include $file);
                    }
                }
                return $this->offsetExists($offset);
            } else {
                $file = "{$this->configDir}/{$this->environment}/{$name}.php";
                if (is_file($file) && is_readable($file)) {
                    $this->container[$name] = include $file;
                    return $this->offsetExists($offset);
                }
            }

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->container[$offset] : null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }
}
