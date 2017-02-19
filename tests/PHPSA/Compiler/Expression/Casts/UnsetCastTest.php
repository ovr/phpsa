<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class UnsetCastTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests (unset) {expr} = null
     */
    public function testUnsetCompile()
    {
        $baseExpression = new Node\Expr\Cast\Unset_(
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        self::assertInstanceOfCompiledExpression($compiledExpression);
        self::assertSame(CompiledExpression::NULL, $compiledExpression->getType());
        self::assertSame(null, $compiledExpression->getValue());
    }
}
