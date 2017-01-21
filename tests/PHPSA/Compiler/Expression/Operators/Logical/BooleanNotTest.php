<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;

/**
 * Class BooleanNotTest
 * @package Tests\PHPSA\Expression\Operators\Logical
 */
class BooleanNotTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, false],
            [false, true],
            [1, false],
            [-1, false],
            [1.4, false],
            [null, true],
            ["a", false],
            [[], true],
        ];
    }

    /**
     * Tests !{expr}
     *
     * @see \PHPSA\Compiler\Expression\Operators\Logical\BooleanNot
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b)
    {
        $baseExpression = new Node\Expr\BooleanNot(
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
        return new Node\Expr\BooleanNot($a);
    }
}
