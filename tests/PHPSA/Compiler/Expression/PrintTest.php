<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class PrintTest extends \Tests\PHPSA\TestCase
{
    public function testPrintOpSuccess()
    {
        $baseExpression = new Node\Expr\Print_(
            $this->newScalarExpr("test")
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        parent::assertSame(1, $compiledExpression->getValue());
    }
}
