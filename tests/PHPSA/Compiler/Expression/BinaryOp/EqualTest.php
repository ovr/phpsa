<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

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
        return [
            [-1, -1],
            [-1, -1.0],
            [-5, -5],
            [-5, -5.0],
            [-5.0, -5],
            [-150, -150],
            [-150.0, -150],
            [-150, -150.0],
            [150, 150],
            [150, 150.0],
            [150.0, 150],
            [150.0, 150.0],
            //boolean true
            [true, true],
            [true, 1],
            [1, true],
            // boolean false
            [false, false],
            [false, 0],
            [0, false],
            // empty arrays
            [[], false],
            [false, []],
            [[], []],
        ];
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
        return [
            [-1, 150],
            [-1, 1],
            [0, 1],
            [1, 0],
            [true, 0],
            [0, true],
            [false, true],
            [false, 1],
            [1, false],
            [true, []],
            [[], true],
        ];
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
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
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
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
