<?php

namespace Tests\PHPSA\Defintion;

use Tests\PHPSA\TestCase;

class ClassDefintionTest extends TestCase
{
    public function testSimpleInstance()
    {
        $classDefinition = new \PHPSA\Definition\ClassDefinition('MyTestClass', 0);
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

    public function testMethodSetGet()
    {
        $classDefinition = $this->testSimpleInstance();
        $methodName = 'method1';
        $nonExistsMethodName = 'method2';

        $this->assertFalse($classDefinition->hasMethod($methodName));
        $this->assertFalse($classDefinition->hasMethod($nonExistsMethodName));

        $classDefinition->addMethod(
            new \PHPSA\Definition\ClassMethod(
                $methodName,
                new \PhpParser\Node\Stmt\ClassMethod(
                    $methodName
                ),
                0
            )
        );

        $this->assertTrue($classDefinition->hasMethod($methodName));
        $this->assertFalse($classDefinition->hasMethod($nonExistsMethodName));

        $method = $classDefinition->getMethod($methodName);
        $this->assertInstanceOf('PHPSA\Definition\ClassMethod', $method);
        $this->assertSame($methodName, $method->getName());

        return $classDefinition;
    }
}
