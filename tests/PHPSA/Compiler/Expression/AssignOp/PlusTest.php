<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class PlusTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for {var} += {expr} with result type = int
     *
     * @return array
     */
    public function plusResultIntDataProvider()
    {
        return array(
            array(2, 2, 4),
            array(true, 2, 3),
            array(3, true, 4),
            array(true, true, 2),
            array(2, 0, 2),
            array(false, -1, -1),
            array(2, false, 2),
            array(false, false, 0),
            array(-2, 1, -1),
            array(true, false, 1),
        );
    }

    /**
     * Tests {var} += {expr} with result type = int
     *
     * @dataProvider plusResultIntDataProvider
     */
    public function testPlusResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Plus(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} += {expr} with result type = double
     *
     * @return array
     */
    public function plusResultDoubleDataProvider()
    {
        return array(
            array(2, -1.5, 0.5),
            array(1.5, 2, 3.5),
            array(true, 1.5, 2.5),
            array(1.5, false, 1.5),
            array(true, -2.5, -1.5),
        );
    }

    /**
     * Tests {var} += {expr} with result type = double
     *
     * @dataProvider plusResultDoubleDataProvider
     */
    public function testPlusResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Plus(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {var-type::UNKNOWN} += {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Plus(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {var} += {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Plus(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
