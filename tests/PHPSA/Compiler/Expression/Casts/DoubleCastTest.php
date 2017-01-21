<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class DoubleCastTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, 1.0],
            [0, 0.0],
            [-1, -1.0],
            [1.4, 1.4],
            ["a", 0.0],
            [[], 0.0],
        ];
    }

    /**
     * Tests (double) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testDoubleCastCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\Double(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Double
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Double($a);
    }
}
