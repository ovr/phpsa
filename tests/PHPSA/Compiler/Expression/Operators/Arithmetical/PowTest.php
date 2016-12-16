<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class PowTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for {expr} ** {expr} == {int}
     *
     * @return array
     */
    public function powResultIntDataProvider()
    {
        return [
            [2, 2, 4],
            [true, 2, 1],
            [3, true, 3],
            [true, true, 1],
            [2, 0, 1],
            [false, 3, 0],
            [2, false, 1],
            [false, false, 1],
            [0, 0, 1],
            [0, 3, 0],
            [true, false, 1],
        ];
    }

    /**
     * Tests {expr} ** {expr} = {int}
     *
     * @dataProvider powResultIntDataProvider
     */
    public function testPowResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\BinaryOp\Pow(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {expr} ** {expr} == {double}
     *
     * @return array
     */
    public function powResultDoubleDataProvider()
    {
        return [
            [2, -2, 0.25],
            [1.5, 2, 2.25],
            [1, 1.5, 1.0],
            [100, 2.5, 100000.0],
            [true, 1.5, 1.0],
            [false, 1.5, 0.0],
            [1.5, false, 1.0],
            [1.5, true, 1.5],
        ];
    }

    /**
     * Tests {expr} ** {expr} = {double}
     *
     * @dataProvider powResultDoubleDataProvider
     */
    public function testPowResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\BinaryOp\Pow(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr::UNKNOWN} ** {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\BinaryOp\Pow(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} ** {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\BinaryOp\Pow(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
