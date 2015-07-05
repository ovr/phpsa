<?php

namespace Tests\PHPSA\Defintion;

use Tests\PHPSA\TestCase;

class ClassDefintionTest extends TestCase
{
    public function testSimpleInstance()
    {
        $classDefinition = new \PHPSA\Definition\ClassDefinition('MyTestClass');
        $this->assertSame('MyTestClass', $classDefinition->getName());

        return $classDefinition;
    }

    public function testSetGetHasForClassProperty()
    {
        $classDefinition = $this->testSimpleInstance();
        $this->assertFalse($classDefinition->hasProperty('test1'));
        $this->assertFalse($classDefinition->hasProperty('test2'));

        $property = new \PhpParser\Node\Stmt\Property(
            0,
            array(
                new \PhpParser\Node\Stmt\PropertyProperty(
                    'test1',
                    new \PhpParser\Node\Scalar\String_(
                        'test string'
                    )
                )
            )
        );
        $classDefinition->addProperty($property);

        $this->assertTrue($classDefinition->hasProperty('test1'));
        $this->assertFalse($classDefinition->hasProperty('test2'));

        $property = new \PhpParser\Node\Stmt\Property(
            0,
            array(
                new \PhpParser\Node\Stmt\PropertyProperty(
                    'test2',
                    new \PhpParser\Node\Scalar\String_(
                        'test string'
                    )
                )
            )
        );
        $classDefinition->addProperty($property);

        $this->assertTrue($classDefinition->hasProperty('test1'));
        $this->assertTrue($classDefinition->hasProperty('test2'));
    }
}
