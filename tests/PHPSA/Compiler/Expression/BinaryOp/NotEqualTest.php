<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;


class NotEqualTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function providerForStaticEqualsTrue()
    {
        return [
            [-1, -2],
            //boolean true
            [true, false],
            [true, 0],
            [0, true],
            // boolean false
            [false, true],
            [false, 1],
            [1, false]
            //@todo arrays....
        ];
    }

    /**
     * Tests {left-expr} != {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticEqualsTrue
     */
    public function testStaticEqualsTrue($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\NotEqual(
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
            [-1, -1],
            [1, 1],
            [1, 1],
            [0, 0],
            [true, 1],
            [1, true],
            [false, 0],
            [false, false]
            //@todo arrays....
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
        $baseExpression = new Node\Expr\BinaryOp\NotEqual(
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
     * @return Node\Expr\BinaryOp\NotEqual
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\NotEqual($a, $b);
    }
}
