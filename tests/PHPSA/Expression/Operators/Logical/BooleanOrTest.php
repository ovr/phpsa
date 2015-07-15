<?php

namespace Tests\PHPSA\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class BooleanOrTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(true, true, true),
            array(true, false, true),
            array(false, true, true),
            array(false, false, false),
            array(null, false, false),
            array(false, null, false),
            array(null, null, false),
            array(true, null, true),
            array(null, true, true),
        );
    }

    /**
     * Tests {expr} || {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testBooleanOr($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\BooleanOr(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }
}
