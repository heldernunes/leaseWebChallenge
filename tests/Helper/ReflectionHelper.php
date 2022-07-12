<?php

namespace App\Tests\Helper;

/**
 * Static methods to access protected/private methods and data
 *
 * To be used only in tests
 */
class ReflectionHelper
{
    /**
     * Invoke a method regardless of its visibility.
     *
     * @param mixed $object Object or class name
     * @param string $method Method name
     * @param array $arguments
     * @return mixed Return value of invoked method
     */
    public static function invokeMethod($object, $method, $arguments = [])
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflectedMethod = $reflectedClass->getMethod($method);
        $reflectedMethod->setAccessible(true);
        return $reflectedMethod->invokeArgs(is_object($object) ? $object : $reflectedClass, $arguments);
    }

    /**
     * Set a value for a property regardless of its visibility
     *
     * @param mixed $object Object or class name
     * @param string $property Property name
     * @param mixed $value Value to set
     */
    public static function setProperty($object, $property, $value)
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflection = $reflectedClass->getProperty($property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * Get a property value regardless of its visibility
     *
     * @param mixed $object Object or class name
     * @param string $property Property name
     * @return mixed Property value
     */
    public static function getProperty($object, $property)
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflection = $reflectedClass->getProperty($property);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }

    /**
     * Get the internal state of an object as an array
     *
     * @param  object $object
     * @return array
     */
    public static function getObjectStateAsArray($object)
    {
        $reflectedClass = new \ReflectionClass($object);
        $variables = $reflectedClass->getProperties();
        $array = [];
        foreach ($variables as $variable) {
            $variable->setAccessible(true);
            $value = $variable->getValue($object);
            $array[$variable->getName()] = $value;
            if (is_object($value)) {
                $array[$variable->getName()] = self::getObjectStateAsArray($value);
            }
            if (!is_array($value)) {
                continue;
            }
            $array[$variable->getName()] = [];
            foreach ($value as $valueIndex => $valueElement) {
                $array[$variable->getName()][$valueIndex] = $valueElement;
                if (is_object($valueElement)) {
                    $array[$variable->getName()][$valueIndex] = self::getObjectStateAsArray($valueElement);
                }
            }
        }
        return $array;
    }
}
