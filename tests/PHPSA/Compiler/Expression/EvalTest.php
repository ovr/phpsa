<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class EvalTest extends \Tests\PHPSA\TestCase
{
    public function testEvalOpSuccess()
    {
        $baseExpression = new Node\Expr\Eval_(
            $this->newScalarExpr("echo 'test';")
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::NULL, $compiledExpression->getType());
        self::assertSame(null, $compiledExpression->getValue());
    }
}
