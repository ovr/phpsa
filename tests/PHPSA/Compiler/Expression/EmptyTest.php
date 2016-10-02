<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Variable;

class EmptyTest extends \Tests\PHPSA\TestCase
{
    public function testEmptyVarInt()
    {
        $context = $this->getContext();
        $context->addVariable(new Variable("name", 10, CompiledExpression::INTEGER));

        $baseExpression = new Node\Expr\Empty_(
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertTrue($compiledExpression->getValue());
    }

    public function testEmptyVarFalse()
    {
        $context = $this->getContext();
        $context->addVariable(new Variable("name", false, CompiledExpression::BOOLEAN));

        $baseExpression = new Node\Expr\Empty_(
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }

    public function testEmptyVarZero()
    {
        $context = $this->getContext();
        $context->addVariable(new Variable("name", 0, CompiledExpression::INTEGER)); // 0 == false because empty(0) == false

        $baseExpression = new Node\Expr\Empty_(
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }

    public function testEmptyVarNull()
    {
        $context = $this->getContext();
        $context->addVariable(new Variable("name", null, CompiledExpression::NULL));

        $baseExpression = new Node\Expr\Empty_(
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }

    public function testEmptyVarNotExisting()
    {
        $context = $this->getContext();

        $baseExpression = new Node\Expr\Empty_(
            new Node\Expr\Variable(
                new Node\Name("name")
            )
        );
        $compiledExpression = $this->compileExpression($baseExpression, $context);

        parent::assertInstanceOfCompiledExpression($compiledExpression);
        parent::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        parent::assertFalse($compiledExpression->getValue());
    }
}
