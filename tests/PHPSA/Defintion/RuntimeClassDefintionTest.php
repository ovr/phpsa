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
            self::assertTrue($definition->hasMethod($method->getName()));
        }

        self::assertFalse($definition->hasMethod('XXXXXXXXXXXXXX'));
    }

    public function testHasConst()
    {
        $reflection = new ReflectionClass('PHPSA\Context');
        $definition = new RuntimeClassDefinition($reflection);

        foreach ($reflection->getConstants() as $constant) {
            self::assertTrue($definition->hasConst($constant));
        }

        self::assertFalse($definition->hasConst('XXXXXXXXX'));
    }

    public function testHasConstWithParent()
    {
        $reflection = new ReflectionClass(Parameter::class);
        $definition = new RuntimeClassDefinition($reflection);

        self::assertFalse($definition->hasConst('BRANCH_ROOT'));
        self::assertTrue($definition->hasConst('BRANCH_ROOT', true));
    }

    public function testHasProperty()
    {
        $reflection = new ReflectionClass('PHPSA\Context');
        $definition = new RuntimeClassDefinition($reflection);

        foreach ($reflection->getProperties() as $property) {
            self::assertTrue($definition->hasProperty($property->getName()));
        }

        self::assertFalse($definition->hasProperty('XXXXX'));
    }
}
