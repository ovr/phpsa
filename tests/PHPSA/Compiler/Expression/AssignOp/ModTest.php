<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class ModTest extends AbstractBinaryOp
{
    /**
     * Data provider for {var} %= {expr} with result type = int
     *
     * @return array
     */
    public function modDataProvider()
    {
        return [
            [2, 2, 0],
            [true, 2, 1],
            [3, true, 0],
            [true, true, 0],
            [-1, 1, 0],
            [false, 3, 0],
            [false, true, 0],
            [0, 1, 0],
            [1, -1, 0],
        ];
    }

    /**
     * Tests {var} %= {expr} with result type = int
     *
     * @dataProvider modDataProvider
     */
    public function testModResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Mod(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\AssignOp\Mod
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\Mod($a, $b);
    }
}
