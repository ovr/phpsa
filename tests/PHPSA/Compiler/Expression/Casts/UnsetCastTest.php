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

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::NULL, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
