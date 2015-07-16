<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class SmallerTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function smallerDataProvider()
    {
        return array(
            array(-1, -1, false),
            array(-2, -1, true),
            array(-3, -1, true),
            array(-50, -1, true),
            array(1, 2, true),
            array(1, 5, true),
            array(6, 5, false),
        );
    }

    /**
     * Tests {int} < {int} = {int}
     *
     * @dataProvider smallerDataProvider
     */
    public function testSmaller($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Smaller(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }
}
