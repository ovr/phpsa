<?php

namespace Tests\PHPSA\Compiler\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

abstract class AbstractUnaryOp extends \Tests\PHPSA\TestCase
{
    /**
     * @param $a
     * @return Node\Expr
     */
    abstract protected function buildExpression($a);


    /**
     * Tests $operator {expr::UNKNOWN}
     */
    public function testUnexpectedType()
    {
        $baseExpression = $this->buildExpression(
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
