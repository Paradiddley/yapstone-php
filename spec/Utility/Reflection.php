<?php

namespace Tests\Utility;

class Reflection
{
    /**
     * Grant access to private and protected properties of an object.
     *
     * @param string|object $class
     * @param string $propertyName
     * @return mixed
     */
    public function getEncapsulatedProperty($class, $propertyName)
    {
        $object = !is_object($class) ? new $class : $class;

        $transformer = new \ReflectionClass($object);
        $property = $transformer->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Allow private and protected static variable assignment.
     *
     * @param string|object $class
     * @param string $name
     * @param mixed $value
     * @return object
     */
    public function setStaticEncapsulatedProperty($class, $name, $value)
    {
        $object = !is_object($class) ? new $class : $class;

        $reflectedClass = new \ReflectionClass($object);
        $reflectedProperty = $reflectedClass->getProperty($name);
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($value);

        return $object;
    }

    /**
     * Grant access to private and protected methods of an object.
     *
     * @param string|object $class
     * @param string $methodName
     * @param array $args
     * @return mixed
     */
    public function invokeEncapsulatedMethod($class, $methodName, $args = [])
    {
        $object = !is_object($class) ? new $class : $class;

        $transformer = new \ReflectionClass(new $object);
        $method = $transformer->getMethod($methodName);
        $method->setAccessible(true);

        if (empty($args)) {
            return $method->invoke($object);
        } else {
            return $method->invokeArgs($object, $args);
        }
    }
}
