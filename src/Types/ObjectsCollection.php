<?php

namespace Ant\Types;

class ObjectsCollection extends \ArrayObject
{
    /**
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        parent::__construct($collection);
    }

    /**
     * @param array $properties
     *
     * @return object|null
     */
    public function findByProperties(array $properties)
    {
        foreach ($this as $object) {
            $countSuccessCompare = 0;
            foreach ($properties as $propertyName => $propertyValue) {
                if (
                    (
                        method_exists($object, 'get' . $propertyName)
                        && strtolower($object->{'get' . $propertyName}()) == strtolower($propertyValue)
                    )
                    || (
                        !empty($object->{$propertyName})
                        && strtolower($object->{$propertyName}) == strtolower($propertyValue)
                    )
                ) {
                    ++$countSuccessCompare;
                }
            }

            if ($countSuccessCompare == count($properties)) {
                return $object;
            }
        }

        return null;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    public function removeByProperties(array $properties)
    {
        foreach ($this as $key => $object) {
            $countSuccessCompare = 0;
            foreach ($properties as $propertyName => $propertyValue) {
                if (
                    (method_exists($object, 'get' . $propertyName)
                        && $object->{'get' . $propertyName}() == $propertyValue)
                    || (!empty($object->{$propertyName}) && $object->{$propertyName} == $propertyValue)
                ) {
                    ++$countSuccessCompare;
                }
            }

            if ($countSuccessCompare == count($properties)) {
                unset($this[$key]);
                return true;
            }
        }

        return false;
    }
}