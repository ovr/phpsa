<?php

namespace Tests\PHPSA\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class BooleanAndTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(true, true, true),
            array(false, true, false),
            array(true, false, false),
            array(false, false, false),
            array(null, null, false),
            array(true, null, false),
            array(null, true, false),
            array(1, true, true),
            array(1.4, true, true),
            array(1, false, false),
            array(-1, true, true),
            array("a", true, true),
            array(array(), array(), false),
            array(array(), "a", false),
        );
    }

    /**
     * Tests {expr} && {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\BooleanAnd(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    public function testUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\BooleanAnd(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
