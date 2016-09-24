<?php

namespace Tests\PHPSA\Compiler\Expression\Casts;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class ObjectCastTest extends \Tests\PHPSA\TestCase
{
    /**
     * Tests (object) {expr} = {expr}
     */
    public function testSimpleSuccessCompile()
    {
        // @todo implement
    }

    public function testUnexpectedType()
    {
        $baseExpression = new Node\Expr\Cast\Object_(
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
