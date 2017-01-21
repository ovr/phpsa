<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class BitwiseXorTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [0, 5, 5],
            [1, 5, 4],
            [4, 5, 1],
            [-1, 5, -6],
            [1.4, 5, 4],
            [-19.7, 1, -20],
            [true, true, 0],
            [false, true, 1],
            [true, false, 1],
            [false, false, 0],
        ];
    }

    /**
     * Tests {expr} ^ {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\BitwiseXor(
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
     * @return Node\Expr\BinaryOp\BitwiseXor
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\BitwiseXor($a, $b);
    }
}
