<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class ObjectCastTest extends AbstractUnaryOp
{
    /**
     * Tests (object) {expr} = {expr}
     */
    public function objectCastDataProvider()
    {
        return [
            [
                CompiledExpression::INTEGER,
                1
            ],
            [
                CompiledExpression::DOUBLE,
                1.0
            ],
            [
                CompiledExpression::ARR,
                [
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            ],
            [
                CompiledExpression::ARR,
                []
            ],
            [
                CompiledExpression::ARR,
                [
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            ],
            [
                CompiledExpression::BOOLEAN,
                true
            ],
            [
                CompiledExpression::RESOURCE,
                STDIN
            ],
            [
                CompiledExpression::STRING,
                'test str'
            ],
            [
                CompiledExpression::NULL,
                null
            ],
        ];
    }

    /**
     * @dataProvider objectCastDataProvider
     *
     * @param int $type
     * @param mixed $value
     */
    public function testObjectCastCompile($type, $value)
    {
        $baseExpression = new Node\Expr\Cast\Object_(
            $this->newFakeScalarExpr(
                $type,
                $value
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::OBJECT, $compiledExpression->getType());
        $this->assertEquals((object) $value, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Object_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Object_($a);
    }
}
