<?php

declare(strict_types=1);

namespace Corephp\Model;

use Exception;
use InvalidArgumentException;

/**
 * Model
 * -----------
 * Model
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
abstract class Model
{
    const TYPE_BOOL = 'boolean';
    const TYPE_INT = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_RAW = 'raw';

    protected array $storage = [];
    protected array $types = [];
    /**
     * fill
     *
     * @param  mixed $data
     * @return void
     */
    public function fill(array $data): void
    {
        foreach ($data as $property => $value) {
            $this->setProperty($property, $value);
        }
    }
    /**
     * hasProperty
     *
     * @param  mixed $property
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        //return property_exists(static::class, $property);
        return array_key_exists($property, $this->types);
    }
    /**
     * getProperty
     *
     * @param  mixed $property
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function getProperty(string $property, $defaultValue = null): mixed
    {
        return $this->{$property} ?? $defaultValue;
    }
    /**
     * setProperty
     *
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    public function setProperty(string $property, $value): void
    {
        $this->{$property} = $value;
    }
    /**
     * getType
     *
     * @param  mixed $property
     * @return string
     */
    public function getType(string $property): string
    {
        return $this->types[$property];
    }
    /**
     * filterBoolean
     *
     * @param  mixed $value
     * @return bool
     */
    public function filterBoolean(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    /**
     * filterInteger
     *
     * @param  mixed $value
     * @return null|int
     */
    public function filterInteger(mixed $value): null|int
    {
        return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }
    /**
     * filterFloat
     *
     * @param  mixed $value
     * @return null|float
     */
    public function filterFloat(mixed $value): null|float
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
    }
    /**
     * filterString
     *
     * @param  mixed $value
     * @return string
     */
    public function filterString(mixed $value): string
    {
        return addslashes($value ?? '');
    }
    /**
     * __set
     *
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    public function __set($property, $value)
    {
        // This magic method is called when a non-existent or inaccessible property is set.
        // The parameter $name contains the name of the property that was set, and $value contains its value.

        // The value of the property is stored in the $data array with the property name as the key.
        if ($this->hasProperty($property)) {
            $type = $this->getType($property);
            switch ($type) {
                case self::TYPE_BOOL:
                    $this->storage[$property] = $this->filterBoolean($value);
                    break;
                case self::TYPE_INT:
                    $this->storage[$property] = $this->filterInteger($value);
                    break;
                case self::TYPE_FLOAT:
                    $this->storage[$property] = $this->filterFloat($value);
                    break;
                case self::TYPE_STRING:
                    $this->storage[$property] = $this->filterString($value);
                    break;
                case self::TYPE_ARRAY:
                    $this->storage[$property] = (array)$value;
                    break;
                case self::TYPE_OBJECT:
                    $this->storage[$property] = (object)$value;
                    break;
                case self::TYPE_RAW:
                    $this->storage[$property] = $value;
                    break;
                default:
                    throw new Exception("'$type' tidak didukung.");
            }
        }
    }
    /**
     * __get
     *
     * @param  mixed $property
     * @return void
     */
    public function __get($property)
    {
        // This magic method is called when a non-existent or inaccessible property is accessed.
        // The parameter $name contains the name of the property that was accessed.

        // The `array_key_exists()` function checks if the specified key exists in the array.
        // In this case, it checks if the property with the name specified in $name exists in the $data array.

        if ($this->hasProperty($property))
            return $this->storage[$property];
        throw new InvalidArgumentException("Property '$property' doesn't exists.");
    }
}
