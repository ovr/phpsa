<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class StringCastTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, "1"],
            [0, "0"],
            [-1, "-1"],
            [1.4, "1.4"],
            ["a", "a"],
        ];
    }

    /**
     * Tests (string) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testStringCastCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\String_(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\String_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\String_($a);
    }
}
