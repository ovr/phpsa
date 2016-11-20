<?php

namespace Tests\PHPSA\Defintion;

use PHPSA\Compiler\Parameter;
use PHPSA\Definition\RuntimeClassDefinition;
use ReflectionClass;
use Tests\PHPSA\TestCase;

class RuntimeClassDefintionTest extends TestCase
{
    const TEST_CLASS_NAME = 'PHPSA\Definition\ClassDefinition';

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

    public function testHasConstWithParent()
    {
        $reflection = new ReflectionClass(Parameter::class);
        $definition = new RuntimeClassDefinition($reflection);

        static::assertFalse($definition->hasConst('BRANCH_ROOT'));
        static::assertTrue($definition->hasConst('BRANCH_ROOT', true));
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
