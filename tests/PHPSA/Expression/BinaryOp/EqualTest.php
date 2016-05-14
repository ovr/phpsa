<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class EqualTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class EqualTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function providerForStaticEqualsTrue()
    {
        return array(
            array(-1, -1),
            array(-1, -1.0),
            array(-5, -5),
            array(-5, -5.0),
            array(-5.0, -5),
            array(-150, -150),
            array(-150.0, -150),
            array(-150, -150.0),
            array(150, 150),
            array(150, 150.0),
            array(150.0, 150),
            array(150.0, 150.0),
            //boolean true
            array(true, true),
            array(true, 1),
            array(1, true),
            // boolean false
            array(false, false),
            array(false, 0),
            array(0, false),
            // empty arrays
            array(array(), false),
            array(false, array()),
            array(array(), array()),
        );
    }

    /**
     * Tests {left-expr} == {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticEqualsTrue
     */
    public function testStaticEqualsTrue($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Equal(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    /**
     * @return array
     */
    public function providerForStaticEqualsFalse()
    {
        return array(
            array(-1, 150),
            array(-1, 1),
            array(0, 1),
            array(1, 0),
            array(true, 0),
            array(0, true),
            array(false, true),
            array(false, 1),
            array(1, false),
            array(true, array()),
            array(array(), true),
        );
    }

    /**
     * Tests {left-expr} == {right-expr} but for false
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticEqualsFalse
     */
    public function testStaticEqualsFalse($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Equal(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(false, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr::UNKNOWN} == {right-expr}
     */
    public function testFirstUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Equal(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} == {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Equal(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
