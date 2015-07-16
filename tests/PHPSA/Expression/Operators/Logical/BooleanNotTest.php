<?php

namespace Tests\PHPSA\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

/**
 * Class BooleanNotTest
 * @package Tests\PHPSA\Expression\Operators\Logical
 */
class BooleanNotTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(true, false),
            array(false, true),
            array(1, false),
            array(-1, false),
        );
    }

    /**
     * Tests !{expr}
     *
     * @see \PHPSA\Visitor\Expression\Operators\Logical\BooleanNot
     * @dataProvider getDataProvider
     */
    public function testBooleanNot($a, $b)
    {
        $baseExpression = new Node\Expr\BooleanNot(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }
}
