<?php

namespace Tests\PHPSA\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;


class BitwiseNotTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(1, -2),
            array(-1, 0),
            array(1.4,-2),
            array(-2.7, 1),
        );
    }

    /**
     * Tests ~{expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b)
    {
        $baseExpression = new Node\Expr\BitwiseNot(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    public function testUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BitwiseNot(
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
