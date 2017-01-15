<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Variable;

class IssetTest extends \Tests\PHPSA\TestCase
{
    public function testIssetVarInt()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');

        $context = $this->getContext();
        $context->addVariable(new Variable("name", 10, CompiledExpression::INTEGER));

        $baseExpression = new Node\Expr\Isset_([
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        ]);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertTrue($compiledExpression->getValue());
    }

    public function testIssetVarNull()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');

        $context = $this->getContext();
        $context->addVariable(new Variable("name", null, CompiledExpression::NULL));

        $baseExpression = new Node\Expr\Isset_([
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        ]);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }

    public function testIssetVarNotExisting()
    {
        $this->markTestSkipped('Unsupported now, because it is not possible to get good results');

        $context = $this->getContext();

        $baseExpression = new Node\Expr\Isset_([
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        ]);
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }
}
