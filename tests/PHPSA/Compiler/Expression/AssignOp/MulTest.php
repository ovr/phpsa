<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class MulTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for {var} *= {expr} with result type = int
     *
     * @return array
     */
    public function mulResultIntDataProvider()
    {
        return array(
            array(2, 2, 4),
            array(true, 2, 2),
            array(3, true, 3),
            array(true, true, 1),
            array(2, 0, 0),
            array(false, 3, 0),
            array(2, false, 0),
            array(false, false, 0),
            array(0, 0, 0),
            array(true, false, 0),
            array(-1, 2, -2),
        );
    }

    /**
     * Tests {var} *= {expr} with result type = int
     *
     * @dataProvider mulResultIntDataProvider
     */
    public function testMulResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} *= {expr} with result type = double
     *
     * @return array
     */
    public function mulResultDoubleDataProvider()
    {
        return array(
            array(2, 1.5, 3.0),
            array(1.5, 1.5, 2.25),
            array(true, 1.5, 1.5),
            array(false, 1.5, 0.0),
            array(1.5, false, 0.0),
            array(1.5, true, 1.5),
            array(-1.5, true, -1.5),
        );
    }

    /**
     * Tests {var} *= {expr} with result type = double
     *
     * @dataProvider mulResultDoubleDataProvider
     */
    public function testMulResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {var-type::UNKNOWN} *= {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Mul(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {var} *= {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Mul(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
