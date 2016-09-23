<?php

namespace Tests\PHPSA\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class StringCastTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(true, "1"),
            array(0, "0"),
            array(-1, "-1"),
            array(1.4, "1.4"),
            array("a", "a"),
        );
    }

    /**
     * Tests (string) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\String_(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    public function testUnexpectedType()
    {
        $baseExpression = new Node\Expr\Cast\String_(
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
