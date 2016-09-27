<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ErrorSuppressTest extends \Tests\PHPSA\TestCase
{
    public function testErrorSuppressSuccess()
    {
        $baseExpression = new Node\Expr\ErrorSuppress(new Node\Expr\Print_(
            $this->newScalarExpr("test")
        ));
        $compiledExpression = $this->compileExpression($baseExpression);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        parent::assertSame(1, $compiledExpression->getValue());
    }
}
