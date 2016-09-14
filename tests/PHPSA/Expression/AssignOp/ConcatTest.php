<?php

namespace Tests\PHPSA\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ConcatTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for {var} .= {expr} with result type = string
     *
     * @return array
     */
    public function testConcatResultStringDataProvider()
    {
        return array(
            array(2, 2, "22"),
            array(true, "a", "1a"),
            array("a", true, "a1"),
            array(true, true, "11"),
            array(-1, 1, "-11"),
            array(false, 3, "3"), // 0 at beginning is dropped
            array(false, true, "1"),
            array(0, -1, "0-1"),
            array(1.5, -1, "1.5-1"),
            array(true, -0.5, "1-0.5"),
            array(false, false, ""),
            array(true, false, "1"),
        );
    }

    /**
     * Tests {var} .= {expr} with result type = string
     *
     * @dataProvider testConcatResultStringDataProvider
     */
    public function testConcatResultString($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Concat(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {var-type::UNKNOWN} .= {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Concat(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {var} .= {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Concat(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
