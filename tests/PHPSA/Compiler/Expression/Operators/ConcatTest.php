<?php

namespace Tests\PHPSA\Compiler\Expression\Operators;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ConcatTest extends \Tests\PHPSA\TestCase
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
     * Tests {left-expr::UNKNOWN} . {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\BinaryOp\Concat(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} . {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\BinaryOp\Concat(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
