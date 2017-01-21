<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class LogicalOrTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, true, true],
            [true, false, true],
            [false, true, true],
            [false, false, false],
            [null, false, false],
            [false, null, false],
            [null, null, false],
            [true, null, true],
            [null, true, true],
            [1, true, true],
            [1.4, false, true],
            [1, false, true],
            [-1, false, true],
            ["a", false, true],
            [[], [], false],
            [[], "a", true],
        ];
    }

    /**
     * Tests {expr} or {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\LogicalOr(
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
     * @return Node\Expr\BinaryOp\LogicalOr
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\LogicalOr($a, $b);
    }
}
