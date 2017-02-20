<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ExitTest extends \Tests\PHPSA\TestCase
{
    public function testExitOpSuccess()
    {
        $baseExpression = new Node\Expr\Exit_(
            $this->newScalarExpr("test")
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        self::assertSame("test", $compiledExpression->getValue());
    }
}
