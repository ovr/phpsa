<?php

namespace Tests\PHPSA\Compiler\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;


class ConcatTest extends AbstractBinaryOp
{
    /**
     * Data provider for {expr} . {expr} = {expr}
     *
     * @return array
     */
    public function concatResultDataProvider()
    {
        return [
            [2, 2, "22"],
            [true, "a", "1a"],
            ["a", true, "a1"],
            [true, true, "11"],
            [-1, 1, "-11"],
            [false, 3, "3"], // 0 at beginning is dropped
            [false, true, "1"],
            [0, -1, "0-1"],
            [1.5, -1, "1.5-1"],
            [true, -0.5, "1-0.5"],
            [false, false, ""],
            [true, false, "1"],
        ];
    }

    /**
     * Tests {expr} . {expr} = {expr}
     *
     * @dataProvider concatResultDataProvider
     */
    public function testConcatResult($a, $b, $c)
    {

        $baseExpression = new Node\Expr\BinaryOp\Concat(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Concat
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Concat($a, $b);
    }
}
