<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class ArrayTest
 * @package Tests\PHPSA\Expression
 */
class ArrayTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests {expr} = array();
     */
    public function testEmptyArray()
    {
        $baseExpression = new Node\Expr\Array_();
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::ARR, $compiledExpression->getType());
        $this->assertSame(array(), $compiledExpression->getValue());
    }

    /**
     * Tests {expr} = array(1, 2, 3, 4, 5, 6);
     */
    public function testArrayWith6IntValues()
    {
        $compiledExpression = $this->compileExpression(
            new Node\Expr\Array_(
                array(
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(1)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(2)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(3)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(4)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(5)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(6)
                    )
                )
            ),
            $this->getContext()
        );

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::ARR, $compiledExpression->getType());
        $this->assertSame(array(1, 2, 3, 4, 5, 6), $compiledExpression->getValue());
    }

    /**
     * Tests {expr} = array(1, 2);
     */
    public function testArrayWith2IntValues()
    {
        $compiledExpression = $this->compileExpression(
            new Node\Expr\Array_(
                array(
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(1)
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(2)
                    )
                )
            ),
            $this->getContext()
        );

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::ARR, $compiledExpression->getType());
        $this->assertSame(array(1, 2), $compiledExpression->getValue());
    }

    /**
     * Tests {expr} = array(...);
     */
    public function testArrayWith2StringIntExpr()
    {
        $compiledExpression = $this->compileExpression(
            new Node\Expr\Array_(
                array(
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(1),
                        $this->newScalarExpr('key1')
                    ),
                    new Node\Expr\ArrayItem(
                        $this->newScalarExpr(2),
                        $this->newScalarExpr('key2')
                    )
                )
            ),
            $this->getContext()
        );

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::ARR, $compiledExpression->getType());
        $this->assertSame(
            array(
                'key1' => 1,
                'key2' => 2
            ),
            $compiledExpression->getValue()
        );
    }
}
