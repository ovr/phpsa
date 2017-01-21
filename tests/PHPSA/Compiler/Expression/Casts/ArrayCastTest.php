<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class ArrayCastTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [[], []],
        ];
    }

    /**
     * Tests (array) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testArrayCastCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\Array_(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::ARR, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Array_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Array_($a);
    }
}
