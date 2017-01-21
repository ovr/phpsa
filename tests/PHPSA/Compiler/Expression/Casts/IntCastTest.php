<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class IntCastTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, 1],
            [0, 0],
            [-1, -1],
            [1.4, 1],
            ["a", 0],
            [[], 0],
        ];
    }

    /**
     * Tests (int) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testIntCastCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\Int_(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Int_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Int_($a);
    }
}
