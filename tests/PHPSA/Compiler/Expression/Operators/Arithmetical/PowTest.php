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
        return array(
            array(2, 2, 4),
            array(true, 2, 1),
            array(3, true, 3),
            array(true, true, 1),
            array(2, 0, 1),
            array(false, 3, 0),
            array(2, false, 1),
            array(false, false, 1),
            array(0, 0, 1),
            array(0, 3, 0),
            array(true, false, 1),
        );
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
        return array(
            array(2, -2, 0.25),
            array(1.5, 2, 2.25),
            array(1, 1.5, 1.0),
            array(100, 2.5, 100000.0),
            array(true, 1.5, 1.0),
            array(false, 1.5, 0.0),
            array(1.5, false, 1.0),
            array(1.5, true, 1.5),
        );
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
