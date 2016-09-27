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

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        parent::assertSame("test", $compiledExpression->getValue());
    }
}
