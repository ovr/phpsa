<?php

namespace Tests\PHPSA\Defintion;

use PHPSA\Definition\RuntimeClassDefinition;
use ReflectionClass;
use Tests\PHPSA\TestCase;

class RuntimeClassDefintionTest extends TestCase
{
    const TEST_CLASS_NAME = 'PHPSA\Definition\ClassDefinition';

    public function testConstruct()
    {
        $definition = new RuntimeClassDefinition(new ReflectionClass(self::TEST_CLASS_NAME));
        $definition->compile($this->getContext());
    }

    public function testHasMethod()
    {
        $reflection = new ReflectionClass(self::TEST_CLASS_NAME);
        $definition = new RuntimeClassDefinition($reflection);

        foreach ($reflection->getMethods() as $method) {
            static::assertTrue($definition->hasMethod($method->getName()));
        }

        static::assertFalse($definition->hasMethod('XXXXXXXXXXXXXX'));
    }

    public function testHasConst()
    {
        $reflection = new ReflectionClass('PHPSA\Context');
        $definition = new RuntimeClassDefinition($reflection);

        foreach ($reflection->getConstants() as $constant) {
            static::assertTrue($definition->hasConst($constant));
        }

        static::assertFalse($definition->hasConst('XXXXXXXXX'));
    }

    public function testHasProperty()
    {
        $reflection = new ReflectionClass('PHPSA\Context');
        $definition = new RuntimeClassDefinition($reflection);

        foreach ($reflection->getProperties() as $property) {
            static::assertTrue($definition->hasProperty($property->getName()));
        }

        static::assertFalse($definition->hasProperty('XXXXX'));
    }
}
