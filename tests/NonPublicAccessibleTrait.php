<?php

namespace Lemon\Event\Tests;

/**
 * Trait NonPublicAccessibleTrait
 *
 * Help access to non-public property and method of an object
 */
trait NonPublicAccessibleTrait
{
    /**
     * Get a non public property of an object
     *
     * @param object $obj
     * @param string $property
     * @return mixed
     */
    protected function getNonPublicProperty($obj, $property)
    {
        if (!is_object($obj) || !is_string($property)) {
            return null;
        }
        $ref = new \ReflectionProperty(get_class($obj), $property);
        $ref->setAccessible(true);

        return $ref->getValue($obj);
    }

    /**
     * Set value for a non public property of an object
     *
     * @param object $obj
     * @param string $property
     * @param mixed  $value
     */
    protected function setNonPublicProperty($obj, $property, $value)
    {
        if (!is_object($obj) || !is_string($property)) {
            return;
        }
        $ref = new \ReflectionProperty(get_class($obj), $property);
        $ref->setAccessible(true);

        $ref->setValue($obj, $value);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $obj    Instantiated object that we will run method on.
     * @param string $method Method name to call
     * @param array  $params Array of parameters to pass into method.
     * @return mixed         Method return.
     * @throws \InvalidArgumentException
     */
    protected function invokeNonPublicMethod($obj, $method, $params = [])
    {
        if (!is_object($obj) || !is_string($method) || method_exists($obj,
                $method)) {
            throw new \InvalidArgumentException();
        }
        $ref = new \ReflectionMethod($obj, $method);
        $ref->setAccessible(true);

        return $ref->invokeArgs($obj, $params);
    }
}
