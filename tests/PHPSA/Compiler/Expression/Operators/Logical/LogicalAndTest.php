<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class LogicalAndTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, true, true],
            [false, true, false],
            [true, false, false],
            [false, false, false],
            [null, null, false],
            [true, null, false],
            [null, true, false],
            [1, true, true],
            [1.4, true, true],
            [1, false, false],
            [-1, true, true],
            ["a", true, true],
            [[], [], false],
            [[], "a", false],
        ];
    }

    /**
     * Tests {expr} and {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\LogicalAnd(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\LogicalAnd
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\LogicalAnd($a, $b);
    }
}
