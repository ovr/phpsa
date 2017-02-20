<?php

namespace Tests\PHPSA;

use PHPSA\ScopePointer;

class ScopePointerTest extends TestCase
{
    /**
     * @covers \PHPSA\ScopePointer::getObject
     */
    public function testGetObject()
    {
        $object = new \stdClass;
        $scopePointer = new ScopePointer($object);

        self::assertSame($object, $scopePointer->getObject());
    }

    /**
     * @covers \PHPSA\ScopePointer::isClassMethod()
     */
    public function testIsClassMethod()
    {
        $class = $this->getMockBuilder('\PHPSA\Definition\ClassMethod')
            ->disableOriginalConstructor()
            ->getMock();
        $scopePointer = new ScopePointer($class);

        self::assertTrue($scopePointer->isClassMethod());
    }

    /**
     * @covers \PHPSA\ScopePointer::isClassMethod()
     * @dataProvider notClassMethodDataProvider
     */
    public function testIsNotClassMethod($object)
    {
        $scopePointer = new ScopePointer($object);

        self::assertFalse($scopePointer->isClassMethod());
    }

    /**
     * @covers \PHPSA\ScopePointer::isFunction()
     */
    public function testIsFunction()
    {
        $function = $this->getMockBuilder('\PHPSA\Definition\FunctionDefinition')
            ->disableOriginalConstructor()
            ->getMock();
        $scopePointer = new ScopePointer($function);

        self::assertTrue($scopePointer->isFunction());
    }

    /**
     * @covers \PHPSA\ScopePointer::isFunction()
     * @dataProvider notFunctionDataProvider
     */
    public function testIsNotFunction($object)
    {
        $scopePointer = new ScopePointer($object);

        self::assertFalse($scopePointer->isFunction());
    }

    public function notClassMethodDataProvider()
    {
        $function = $this->getMockBuilder('\PHPSA\Definition\FunctionDefinition')
            ->disableOriginalConstructor()
            ->getMock();

        return [
            'String' => ['Random characters'],
            'Integer' => [42],
            'Float' => [pi()],
            'StdClass' => [new \StdClass],
            'FunctionDefinition' => [$function]
        ];
    }

    public function notFunctionDataProvider()
    {
        $class = $this->getMockBuilder('\PHPSA\Definition\ClassMethod')
            ->disableOriginalConstructor()
            ->getMock();
        
        return [
            'String' => ['Random characters'],
            'Integer' => [42],
            'Float' => [pi()],
            'StdClass' => [new \StdClass],
            'ClassMethod' => [$class]
        ];
    }
}
