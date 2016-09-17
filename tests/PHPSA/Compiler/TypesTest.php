<?php

namespace Tests\PHPSA\Compiler;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Types;
use Tests\PHPSA\TestCase;

class TypesTest extends TestCase
{
    /**
     * @dataProvider typesAsStringProvider
     */
    public function testGetType($typeAsString, $expectedType)
    {
        static::assertSame($expectedType, Types::getType($typeAsString));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Type 'not a valid type' is not supported
     */
    public function testGetTypeWithAnUnknownTypeThrows()
    {
        Types::getType('not a valid type');
    }

    public function typesAsStringProvider()
    {
        return [
            ['integer', CompiledExpression::INTEGER],
            ['int', CompiledExpression::INTEGER],
            ['double', CompiledExpression::DOUBLE],
            ['string', CompiledExpression::STRING],
            ['resource', CompiledExpression::RESOURCE],
            ['callable', CompiledExpression::CALLABLE_TYPE],
            ['object', CompiledExpression::OBJECT],
            ['array', CompiledExpression::ARR],
            ['boolean', CompiledExpression::BOOLEAN],
            ['bool', CompiledExpression::BOOLEAN],
            ['NULL', CompiledExpression::NULL],
        ];
    }
}
