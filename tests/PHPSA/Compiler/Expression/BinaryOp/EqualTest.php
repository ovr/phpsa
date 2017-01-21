<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

/**
 * Class EqualTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class EqualTest extends AbstractBinaryOp
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
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Equal
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Equal($a, $b);
    }
}
