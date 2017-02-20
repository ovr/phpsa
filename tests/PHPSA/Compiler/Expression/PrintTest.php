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

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        self::assertSame(1, $compiledExpression->getValue());
    }
}
