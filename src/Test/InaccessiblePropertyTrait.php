<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Test;

use ReflectionClass;
use ReflectionException;

/**
 * Provides inaccessible property getter and setter method for test classes.
 */
trait InaccessiblePropertyTrait
{
    /**
     * Get an object's private or protected property.
     *
     * @throws ReflectionException Property does not exist.
     */
    protected function getInaccessibleProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        return $reflectionProperty->getValue($object);
    }

    /**
     * Set an object's private or protected property.
     *
     * @throws ReflectionException Property does not exist.
     */
    protected function setInaccessibleProperty(object $object, string $property, mixed $value): void {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}
