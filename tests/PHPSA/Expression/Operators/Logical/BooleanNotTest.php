<?php

namespace Tests\PHPSA\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class BooleanNotTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(true, -1),
            array(false, 0),
            array(1, -1),
            array(-1, 1),
        );
    }

    /**
     * Tests !{expr}
     *
     * @dataProvider getDataProvider
     */
    public function testBooleanNot($a, $b)
    {
        $baseExpression = new Node\Expr\BooleanNot(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame($b, $compiledExpression->getValue());
    }
}
