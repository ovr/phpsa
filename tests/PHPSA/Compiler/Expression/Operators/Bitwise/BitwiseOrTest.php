<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class BitwiseOrTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [0, 5, 5],
            [1, 5, 5],
            [4, 5, 5],
            [-1, 5, -1],
            [1.4, 5, 5],
            [-19.7, 1, -19],
            [true, true, 1],
            [false, true, 1],
            [true, false, 1],
            [false, false, 0],
        ];
    }

    /**
     * Tests {expr} | {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\BitwiseOr(
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
     * @return Node\Expr\BinaryOp\BitwiseOr
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\BitwiseOr($a, $b);
    }
}
