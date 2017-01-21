<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

class BoolCastTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, true],
            [0, false],
            [-1, true],
            [1.4, true],
            ["a", true],
            [[], false],
        ];
    }

    /**
     * Tests (bool) {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testBoolCastCompile($a, $b)
    {
        $baseExpression = new Node\Expr\Cast\Bool_(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\Cast\Bool_
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\Cast\Bool_($a);
    }
}
