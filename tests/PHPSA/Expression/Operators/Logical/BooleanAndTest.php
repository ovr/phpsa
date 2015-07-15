<?php

namespace Tests\PHPSA\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

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
        );
    }

    /**
     * Tests {expr} && {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testBooleanOr($a, $b, $c)
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
}
